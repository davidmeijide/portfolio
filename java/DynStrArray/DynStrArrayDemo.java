package DynStrArray;
public class DynStrArrayDemo {
    public static void main(String[] args) {
        // Crea un DynStrArray con el constructor por defecto.
        DynStrArray d = new DynStrArray();

        //Imprime el valor tamaño de cada array
        System.out.print(d.size());

        //Añadirá cadenas hasta superar el tamaño inicial por defecto
        int i=0;
        while(i++<20){
            d.add("hola");
        }

        //Imprime el número de elmentos del array
        System.out.print(d.size());

        //Imprime el array
        System.out.println(d);

    }
}