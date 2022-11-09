package DynStrArray;
import java.util.Arrays;

public class DynStrArray{
    String[] elementos;

    DynStrArray(){
        elementos = new String[10];
    }
    DynStrArray(int n){
        elementos = new String[n];
    }
    DynStrArray(String[] arr){
        elementos = arr.clone();
    }
    DynStrArray(DynStrArray obj){
        elementos = obj.elementos.clone();
    }
    
    public int size(){
        for(int i=0; i<size(); i++){
            if (elementos[i] == null){
                return i+1;
            }
        }
        return elementos.length;
    }

    public Boolean isEmpty(){
        for (String var : elementos) {
            if(var != null) return false;
        }
        return true; 
    }

    public void clear(){
        for (int i=0; i<size(); i++) {
            elementos[i] = null;
        }
    }

    public String get(int index){
        return elementos[index];
    }

    public void add(String cadena){
        int firstNull = -1;
        for(int i=0; i<size(); i++){
            if (elementos[i] == null){
                firstNull = i;
                break;
            }
        if(firstNull == -1){
            Arrays.copyOf(elementos, (int)(size()*1.5));
        }
        elementos[firstNull] = cadena;
        }
    }

    public void add(int index, String cadena){
        int firstNull = -1;
        for(int i=0; i<size(); i++){
            if (elementos[i] == null){
                firstNull = i;
                break;
            }
        }
        if(firstNull == -1){
            Arrays.copyOf(elementos, (int)(size()*1.5));
        }
        //desplaza los elementos a partir del indice a la derecha
        for(int i=size()-1; i>=index;i--){
            elementos[i-1] = elementos[i];
        }
        //modificamos el indice introducido
        elementos[index] = cadena;
    }

    public void set(int index, String cadena){
        elementos[index] = cadena;
    }

    public void remove(int index){
        for(int i=index; i<size(); i++){
            elementos[i] = elementos[i-1];
        }
    }

    public int indexOf(String cadena){
        for (int i=0; i<size(); i++){
            if(cadena.equals(elementos[i])) return i;
        
        }
        return -1;
    }
    @Override
    public String toString(){
        String s= "[";
        boolean first = true;
        for (String var : elementos) {
            if(first == true) {
                s+=",";
                first = false;
            }
            s += var;
            
        }
        return (s + "]");
    }
    



}