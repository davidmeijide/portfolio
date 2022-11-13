import java.util.Scanner;
import java.util.Arrays;
public class Risk{
    public static void main(String[] args){
        Scanner cin = new Scanner(System.in);

        while(cin.hasNext()){
            //bucle que coje dos lineas de entrada por cada vuelta.
            int[] l1 = new int[5];

            for(int i=0; i<5; i++){
                l1[i] = cin.nextInt();
            }

            System.out.println(Arrays.toString(l1));

            //tropas de defensa
            int td = l1[0];

            // número de tropas de ataque
            int ta = l1[1];

            //dados defensa
            int dd = l1[2];

            //dados ataque
            int da = l1[3];

            //numero oleadas
            int no = l1[4];


        
            
                //Bucle que marca las oleadas
                for(int ole=0; ole<no;ole++){

                    // tiradas que el atacante puede realizar
                    //EOF ctrl+d
                    int tiradasAtk;
                    tiradasAtk = (ta < da) ? ta : da;
                    System.out.println("Tiradas atacante = " + tiradasAtk);

                    int tiradasDef = (td < dd) ? td : dd;
                    System.out.println("Tiradas defensor = " + tiradasDef);


                    //tirada defensa
                    int [] lanzaDef = new int [tiradasDef];
                    for(int i=0;i<tiradasDef;i++){
                        lanzaDef[i] = cin.nextInt();
                    }
                    System.out.println("Array sin ordenar" + Arrays.toString(lanzaDef));
                    Arrays.sort(lanzaDef);
                    invierteLista(lanzaDef);
                    System.out.println(Arrays.toString(lanzaDef));


                    //tirada ataque
                    int [] lanzaAtk = new int [tiradasAtk];
                    for(int i=0;i<tiradasAtk;i++){
                        lanzaAtk[i] = cin.nextInt();
                    }
                    System.out.println(Arrays.toString(lanzaAtk));
                    Arrays.sort(lanzaAtk);
                    invierteLista(lanzaAtk);
                    System.out.println(Arrays.toString(lanzaAtk));

                    //Calculo del ganador de la oleada
                    // 1º miramos quien tiene menos dados para saber cuantos dados comparamos
                    int menosDados = (lanzaDef.length<lanzaAtk.length)?lanzaDef.length:lanzaAtk.length;

                    //Ahora sabemos hasta qué indice vamos a comparar.
                    for(int k=0; k<menosDados;k++){
                        if(lanzaDef[k]>=lanzaAtk[k]){
                            tiradasAtk--;
                            ta--;
                        }
                        else{
                            tiradasDef--;
                            td--;
                        }
                    }
                    System.out.println("Puntos defensor: " + td);
                    System.out.println("Puntos atacante: " + ta);
                }
        }

    }
    /**
     * 
     * @param listaInvertir La lista de ints a invertir.
     * @return Devuelve una nueva lista invertida
     */
    public static void invierteLista(int[] listaInvertir){
        for (int i = 0; i < listaInvertir.length / 2; i++) {
            int temp = listaInvertir[i];
            listaInvertir[i] = listaInvertir[listaInvertir.length - 1 - i];
            listaInvertir[listaInvertir.length - 1 - i] = temp;
        }
    }
}