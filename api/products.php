<?php
//class Products{};

Class Product
{
    public $db = null;
    protected static $dbhost = "localhost";
    protected static $dbuser = "root";
    protected static $dbpass = "";
    protected static $dbname = "db_rest";

    public function __construct()
    {
        $this->db = new mysqli(self::$dbhost, self::$dbuser, self::$dbpass, self::$dbname);
        if (mysqli_connect_errno()) {
            printf("Подключение невозможно:");
            exit();
        }
    }

    public function __destruct()
    {
        $this->db->close();
    }

    public function getAllProducts(){
        $sql = "SELECT * FROM products ORDER BY nameProducts ASC";
        $result = $this->db->query($sql);
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
        return $products;
    }

    public function getProducts($idProducts){

        $idProducts = $this->clearInt($idProducts);

        $product = [];
        $sql = "SELECT * FROM products WHERE idProducts=$idProducts";
        $result = $this->db->query($sql);
        while ($row = $result->fetch_assoc()) {
            $product[] = $row;
        }

        return $product;
    }

    protected function clearInt($data)
    {
        return abs((int)$data);
    }

    public function insertProducts($nameProducts, $category, $PurchasePrice, $sellingPrice)
    {

        $nameProducts = (string)$nameProducts;
        $category = (string)$category;
        $PurchasePrice = (int)$PurchasePrice;
        $sellingPrice = (int)$sellingPrice;

        $sql = "INSERT INTO products (nameProducts, category, PurchasePrice, sellingPrice) VALUES (?,?,?,?)";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssii", $nameProducts, $category, $PurchasePrice, $sellingPrice);
        $stmt->execute();
        $status = $stmt->affected_rows;
        $stmt->close();

        return $status;
    }
}
?>