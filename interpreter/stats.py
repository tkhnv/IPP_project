from sets import instructionSet
class Stats:

    _instance = None

    def __new__(self):
        '''
        Initiates Stats class object (is a singleton)
        '''
        if not self._instance:
            self._instance = super(Stats, self).__new__(self)
        return self._instance

    def __init__(self):
        
        self.insts = None                                   # --insts
        self.hot = None                                     # --hot
        self.vars = None                                    # --vars
        self.currentVars = None                             # counter for current number of initialized variables
        self.statsGroups = {}                               # dictionary of shape <filename> => [statsopt1, statsopt2, ..., statsoptN]

        self.hotInstructions = {}                           # dictionary of shape <instructionOpCode => [useCount, firstAppear]
        for instr in instructionSet:
            self.hotInstructions[instr] = [0, 1]

    def updateInsts(self):
        self.insts += 1

    def updateVars(self, num):
        '''
        Updates number of initialized variables
        '''
        self.currentVars += num
        if self.currentVars > self.vars:
            self.vars = self.currentVars

    def updateHot(self, instruction):
        '''
        Updates "hot" stats for given instruction
        '''
        self.hotInstructions[instruction.opCode][0] += 1
        if self.hotInstructions[instruction.opCode][1] > instruction.order:
            self.hotInstructions[instruction.opCode][1] = instruction.order

    def __findTheHottest(self):
        '''
        Finds the most used instruction, if there are many with the same number of use, finds one that appeared earlier in code.
        Returns order.
        '''
        return list(sorted(list(self.hotInstructions.items()), key=lambda x: (x[1][0], 1/x[1][1]), reverse=True))[0][1][0] # too lon' :(
    

