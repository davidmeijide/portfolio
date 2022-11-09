import java.util.Scanner;
import java.util.Arrays;
import java.util.Random;

public class LifeGame{

    private int rows,cols; 
    private int gen=1;
    private int nvivas=0;
    private long semilla;
    
    private int[][] testData;

    private int[][] r1,w1,w2;
    

    LifeGame(int[][]data){
        this.testData = data;
        this.rows=testData.length;
        this.cols=testData[0].length;
        r1 = Arrays.copyOf(testData, testData.length);

        w2 = new int [rows][cols];
    }
    LifeGame(int rows, int cols, int nvivas){
        this.rows=rows;
        this.cols=cols;
        //nvivas = numVivas.nextInt(rows*cols);
        this.nvivas=nvivas;
        this.testData = new int [rows][cols];
        r1 = Arrays.copyOf(testData, testData.length);

        w2 = new int [rows][cols];
        rellenaArray(nvivas, testData);
        barajaCartas(testData);
        
        
            
        
    }
    LifeGame(int rows, int cols, int nvivas, long semilla){
        this.rows=rows;
        this.cols=cols;
        this.nvivas=nvivas;
        this.semilla=semilla;
        this.testData = new int [rows][cols];
        r1 = Arrays.copyOf(testData, testData.length);

        w2 = new int [rows][cols];
        rellenaArray(nvivas, testData);
        barajaCartas(testData,semilla);


    }
    
    //Métodos

    public void setRows(int rows){
        this.rows=rows;}
    public int getRows(){
        return this.rows;}


    public void setCols(int cols){
        this.cols=cols;}

    public int getCols(){
        return this.cols;}      


    public void setTestData(int[][] data){
        this.testData=data;}
        
    public int[][] getMatrix(){
        return this.testData;
    }

    public int[][] getR1(){
        return this.r1;
    }
    public void setR1(int[][]r1){
        this.r1=r1;
    }

    
    public int[][] getW1(){
        return this.w1;
    }
    public void setW1(int[][]w1){
        this.w1=w1;
    }


    public int[][] getW2(){
        return this.w2;
    }
    public void setW2(int[][]w2){
        this.w2=w2;
    }
    public int getGen(){
        return this.gen;
    }
    public int getNcells(){
        int alive=0;
        for (int[] arr : r1) {
            for(int i : arr){
                if(i==1){
                    alive++;
                }
            }
        }

        return alive;
    }
    public void setNvivas(int nvivas){
        this.nvivas=nvivas;
    }
    public void printMatrix(char c, char separador){
        for(int linea[]:r1){
            for (int elemento : linea) {
                char elementoChar =  (char)elemento;
                if(elementoChar==0){elementoChar=separador;}
                if(elementoChar==1){elementoChar=c;}

            System.out.print(elementoChar);
            }
        System.out.println();
        }

    }
    public void evolve(int nGen){
        nvivas=0;
        //1er bucle: generaciones que queremos avanzar.
        for (int i=0;i<nGen;i++){
            //recorremos todos las filas de r1(1er array de lectura)
            for(int j=0;j<rows;j++){ // j = fila en la que estamos

                //dentro de r1 recorremos cada subArray
                for(int k=0;k<cols;k++){// k = columna en la que estamos

                    if(r1[j][k]==0){
                        if(cuentaVivos(k, j)==3){
                            w2[j][k]=1;
                        }
                    }
                    if(r1[j][k]==1){
                        if((cuentaVivos(k,j)-1)==2 || (cuentaVivos(k,j)-1)==3 ){
                            w2[j][k]=1;
                        }
                        else{
                            w2[j][k]=0;
                        }
                    }
                }
            }

            //    r1=Arrays.copyOf(w2, w2.length);
            for(int y=0;y<w2.length;y++){
                for(int u=0; u<w2[0].length;u++){
                    r1[y][u] = w2[y][u];
                }
            }
            gen++;

        } 
        
    }
    /**
     * Cuenta el numero de celulas vivas alrededor de la coordenada que se le pasa como argumento.
     * @param x eje x en el array bidimiensional (columna).
     * @param y eje y en el array bidimiensional (fila).
     * @return devuelve un int con las veces que encontró celulas vivas.
     */
    public int cuentaVivos(int x,int y){
        int vivos=0;
        for(int i=y-1;i<y+2;i++){
            for(int j=x-1;j<x+2;j++){
                if((i>=0 && j>=0)&&(i<r1.length && j<r1[0].length)){ //marcamos los limites de la tabla.
                    if(r1[i][j]==1){ // Si en esa coordenada hay un 1, suma 1 vivo.
                        vivos++;
                    }
                }
            }
        }
        return vivos;
    }
        
    public void rellenaArray(int nvivas, int[][]array){
        int contador=0;
        outerloop:
        for(int i=0;i<array.length;i++){
            for(int j=0;j<array[0].length;j++){
                array[i][j]=1;
                if(contador>=nvivas){
                    break outerloop;}
                contador++;
            }
        }
    }
    public void barajaCartas(int[][] arr) {
        Random random = new Random();
        for (int i = arr.length - 1; i > 0; i--) {
            for (int j = arr[i].length - 1; j > 0; j--) {
                int m = random.nextInt(i + 1);
                int n = random.nextInt(j + 1);
    
                int temp = arr[i][j];
                arr[i][j] = arr[m][n];
                arr[m][n] = temp;
            }
        }
    }
    /**
     * Añade la opción de usar una semilla para barajar
     */
    public void barajaCartas(int[][] arr, long semilla) {
        Random random = new Random(semilla);
        for (int i = arr.length - 1; i > 0; i--) {
            for (int j = arr[i].length - 1; j > 0; j--) {
                int m = random.nextInt(i + 1);
                int n = random.nextInt(j + 1);
    
                int temp = arr[i][j];
                arr[i][j] = arr[m][n];
                arr[m][n] = temp;
            }
        }
    }






    public static void main(String[] args){
        Scanner cin = new Scanner(System.in);
        
        //int[][]arrayEntrada = {{0,0,0,0,1},{1,1,1,1,0},{0,0,0,1,1},{0,0,0,1,0}};
        //LifeGame life = new LifeGame(arrayEntrada);
        //life.setTestData(arrayEntrada);
        //life.getMatrix();
        //life.printMatrix('X','.');
        	
        int[][] testData = { {0,0,0,0,1},
        {1,1,1,1,0},
        {0,0,0,1,1},
        {0,0,0,1,0}};
        LifeGame life = new LifeGame(testData);
        life.evolve(3);
        life.printMatrix('#','.');

        }
}