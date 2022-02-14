<?php

    include 'sets.php';

    class Stats {

        private $groups = array();
        private $files = array();

        private $loc = 0;                           // --loc
        private $comments = 0;                      // --comments
        private $labels = 0;                        // --labels
        private $jumps = 0;                         // --jumps
        private $fwjumps = 0;                       // --fwjumps
        private $backjumps = 0;                     // --backjumps
        private $badjumps = 0;                      // --badjumps
        
        private $labelIds = array();
        private $jumpDestinations = array();
        
        /**
         * Pushes array to stats groups array
         * 
         * @param array $group new stats group
         */
        public function addToGroups($group) {
            array_push($this->groups, $group);
        }

        /**
         * Extracts filename from *--stats=...*,
         * pushes new file into files array
         * 
         * @param array $file new file name
         */
        public function addToFiles($file) {
            array_push($this->files, substr($file, 8));
        }

        public function setInstructions($count) {
            $this->loc = $count - 1;
        }

        public function incComments() {
            $this->comments++;
        }
        
        public function addLabel($label) {
            if (array_search($label, $this->labelIds) == true) 
                exit(ERR_SYNTAX);
            array_push($this->labelIds, $label);
        }

        public function addJump($label) {
            array_push($this->jumpDestinations, $label);
            if (array_search($label, $this->labelIds) == true) {
                $this->backjumps++;
            }
        }

        public function setJumpsAndLabels() {
            $this->jumps = count($this->jumpDestinations);
            $this->labels = count($this->labelIds);
            foreach ($this->jumpDestinations as $jump) {
                if (!array_search($jump, $this->labelIds)) 
                    $this->badjumps++;
            }
            $this->fwjumps = $this->jumps - $this->badjumps - $this->backjumps;
        }

        /**
         * Prints all stats groups to corresponding files
         */
        public function writeStats() {
            // check if there are duplicates in files array
            if (count($this->files) != count(array_unique($this->files)))
                exit(ERR_OUTPUT);
            
            for ($i = 0; $i < count($this->files); $i++) {
                $file = fopen($this->files[$i], "w");
                $statsText = "";
                for ($j = 0; $j < count($this->groups[$i]); $j++) {
                    $statsText .= $this->{$this->groups[$i][$j]}."\n";
                }
                if (!fwrite($file, $statsText))
                    exit(ERR_OUTPUT);
                fclose($file);
            }
        }

    }

?>