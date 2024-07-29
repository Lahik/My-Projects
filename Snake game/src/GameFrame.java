import javax.swing.ImageIcon;
import javax.swing.JFrame;

public class GameFrame extends JFrame {
    
    GameFrame() {

        this.setIconImage(new ImageIcon("snake.png").getImage());
        this.add(new GamePanel());
        this.setTitle("Snake");
        this.setDefaultCloseOperation(EXIT_ON_CLOSE);
        this.setResizable(false);
        this.pack();
        this.setVisible(true);
        this.setLocationRelativeTo(null);
        
    }

}
