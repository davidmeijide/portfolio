
class Node:
    def __init__(self, state, parent, action):
        self.state = state
        self.parent = parent
        self.action = action
        
class StackFrontier():
    def __init__(self):
        self.frontier = []
    def add(self, node):
        self.frontier.append(node)
    def contains_state(self, state):
        for i in self.frontier:
            if i.state == state:
                return True
        return False
    def empty(self):
        return len(self.frontier) == 0
    def remove(self):
        if self.empty():
            raise Exception ("Empty frontier")
        else:
            node = self.frontier[-1]
            self.frontier = self.frontier[:-1]
            return node

class QueueFrontier(StackFrontier):
    def remove(self):
        if self.empty():
            raise Exception ("Empty frontier")
        else:
            node = self.frontier[0]
            self.frontier = self.frontier[1:]
            return node




class Maze():
    def __init__(self, start, food, rows, cols, grid):
        
        self.start = Node(start, None, None)
        self.food = Node(food, None, None)
        self.rows = rows
        self.cols = cols
        self.grid = grid

    
    def print(self):
        for i in self.grid:
            for j in i:
                print(j,end="")
            print()

    def neighbors(self, state):
        state = state.state
        candidates = []
        row = state[0]
        col = state[1]
        candidates = [
                    ("up",(row-1,col)),
                    ("left",(row, col-1)),
                    ("right",(row, col+1)),
                    ("down",(row+1,col))
                    ]
        result = []
        for direction, (r,c) in candidates:
            if 0 <= r < self.rows and 0 <= c < self.cols and (self.grid[r][c] == "-" or self.grid[r][c] == "."):
                result.append((direction, (r,c)))
        return result



    def solve(self):
        self.q = QueueFrontier()
        # Start with a queue that contains the initial state
        self.q.add(self.start)

        # Start with a an empty explored set
        explored = []

        while True:
            if self.q.empty():
                raise Exception ("No solution")
                break

            # Remove a node from the frontier
            current_node = self.q.remove()

            # If the current node is the food, solved!
            if current_node.state == self.food.state:
                backtracking = []
                backtracking.append(current_node.state)
                while True:
                    if current_node.parent is None:
                        break
                    backtracking.append(current_node.parent.state)
                    current_node = current_node.parent
                backtracking.reverse()
                explored.append(self.food)
                for i in explored:
                    print(i.state)
                return backtracking
                        

            # Add current node to the explored set
            for i in explored:
                if i.state == current_node.state:
                    break
            else:
                explored.append(current_node)

            # Add neighbors to the frontier if not already in frontier or explored
            
            #for node in self.neighbors(current_node.state):
            #    if not node == any(i.state for i in explored) and not self.q.contains_state(node):
            #        self.q.add(Node(node[1],current_node,node[0]))
            
          

            neighbor_list = self.neighbors(current_node)
            
            for i in neighbor_list:
                coords = i[1]
                if self.q.contains_state(i):
                        break
                for j in explored:
                    if j.state == coords:
                        break

                else:
                    new_node = Node(coords, current_node, i[0])
                    self.q.add(new_node)


    def paint_solution(self):
        for coord in m.solve()[1:-1]:
            self.grid[coord[0]][coord[1]] = "*"
        
                    



start = tuple( [int(x) for x in input().split()])
food = tuple([int(x) for x in input().split()])
rows, cols = [int(x) for x in input().split()]
my_grid = []
for i in range(int(rows)):
    my_grid.append(list(input()))
print("Iniciating maze pathfinder BFS...\n")
m = Maze(start, food, rows, cols, my_grid)

print("Solving maze...")

m.paint_solution()
m.print()


#   SAMPLE INPUT
#   3 9
#   5 1
#   7 20
#   %%%%%%%%%%%%%%%%%%%%
#   %--------------%---%
#   %-%%-%%-%%-%%-%%-%-%
#   %--------P-------%-%
#   %%%%%%%%%%%%%%%%%%-%
#   %.-----------------%
#   %%%%%%%%%%%%%%%%%%%%


















