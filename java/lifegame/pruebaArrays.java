import java.util.Arrays;

public class pruebaArrays {
    public static void main(String[] args) {

	
        int[][] testData = { {0,0,0,0,1},
        {1,1,1,1,0},
        {0,0,0,1,1},
        {0,0,0,1,0}};
        LifeGame life = new LifeGame(testData);
        life.evolve(3);
        life.printMatrix('#','.');
        
    }


}
