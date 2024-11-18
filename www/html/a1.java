import java.util.Scanner;

public class MagneticValues {
    public static void main(String[] args) {
        Scanner scanner = new Scanner(System.in);
        int N = scanner.nextInt();  // Read number of test cases
        
        for (int i = 0; i < N; i++) {
            // Read magnetic values for positions (0,0), (0,1), (1,0), and (1,1)
            int a = scanner.nextInt();
            int b = scanner.nextInt();
            int c = scanner.nextInt();
            int d = scanner.nextInt();
            
            // Calculate maximum total magnetic value
            int maxMagneticValue = a + b + c + d;
            System.out.println(maxMagneticValue);
        }
        
        scanner.close();
    }
}
