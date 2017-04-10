<?php 
         $filepath = realpath(dirname(__FILE__));
        include_once ($filepath.'/../lib/Database.php');
        include_once ($filepath.'/../helpers/Format.php');
       
?>
<?php
class Product{
   private $db; 
   private $fm;
    
    public function __construct(){
       
     $this->db = new Database();
     $this->fm = new Format();
    }
    
    public function productInsert($data,$file){
         $productName = mysqli_real_escape_string($this->db->link, $data['productName']);
         $catId       = mysqli_real_escape_string($this->db->link, $data['catId']);
         $brandId     = mysqli_real_escape_string($this->db->link, $data['brandId']);
         $body        = mysqli_real_escape_string($this->db->link, $data['body']);
         $price       = mysqli_real_escape_string($this->db->link, $data['price']);
         $type        = mysqli_real_escape_string($this->db->link, $data['type']);
        
    $permited  = array('jpg', 'jpeg', 'png', 'gif');
    $file_name = $file['image']['name'];
    $file_size = $file['image']['size'];
    $file_temp = $file['image']['tmp_name'];

    $div = explode('.', $file_name);
    $file_ext = strtolower(end($div));
    $unique_image = substr(md5(time()), 0, 10).'.'.$file_ext;
    $uploaded_image = "uploads/".$unique_image;
        
        if($productName == "" || $catId == "" || $brandId == "" || $body == "" || $price == "" || $file_name == "" || $type == "" ){
            
            $msg = "<span class='error'> Fields must not be empty</span>";
            return $msg;
        }
           elseif ($file_size >1048567) {
             echo "<span class='error'>Image Size should be less then 1MB!
          </span>";
         } elseif (in_array($file_ext, $permited) === false) {
             echo "<span class='error'>You can upload only:-"
            .implode(', ', $permited)."</span>";
          }
 
        else{
            move_uploaded_file($file_temp, $uploaded_image);
            $query = "insert into tbl_product(productName,catId,brandId,body,price,image,type) values('$productName','$catId','$brandId','$body','$price','$uploaded_image','$type')";
            $inserted_row = $this->db->insert($query);
            if($inserted_row){
                $msg = "<span class='success'>Product inserted successfully!</span>";
                return $msg;
            }
            else{
                $msg = "<span class='error'>Product NOT inserted !</span>";
                return $msg;
            }
        }

        
        
    }
    public function getAllProduct(){
        
        
        /*
        $query ="SELECT  tbl_product.*,tbl_category.catName,tbl_brand.brandName FROM tbl_product
                 INNER JOIN tbl_category
                 ON tbl_product.catId = tbl_category.catId
                 INNER JOIN tbl_brand
                 ON tbl_product.brandId = tbl_brand.brandId
                 ORDER BY tbl_product.productId DESC";
                 
                 */
         $query = "select p.*,c.catName,b.brandName 
                  from tbl_product as p,tbl_category as c,tbl_brand as b
                   where p.catId = c.catId AND p.brandId = b.brandId
                   order by p.productId desc";
                    
        
        $result = $this->db->select($query);
        return $result;
        
    }
    public function getProById($id){
        $query = "select * from tbl_product where productId = '$id'";
        $result = $this->db->select($query);
        return $result;
        
    }
    public function productUpdate($data, $file, $id){
        
         $productName = mysqli_real_escape_string($this->db->link, $data['productName']);
         $catId       = mysqli_real_escape_string($this->db->link, $data['catId']);
         $brandId     = mysqli_real_escape_string($this->db->link, $data['brandId']);
         $body        = mysqli_real_escape_string($this->db->link, $data['body']);
         $price       = mysqli_real_escape_string($this->db->link, $data['price']);
         $type        = mysqli_real_escape_string($this->db->link, $data['type']);
        
    $permited  = array('jpg', 'jpeg', 'png', 'gif');
    $file_name = $file['image']['name'];
    $file_size = $file['image']['size'];
    $file_temp = $file['image']['tmp_name'];

    $div = explode('.', $file_name);
    $file_ext = strtolower(end($div));
    $unique_image = substr(md5(time()), 0, 10).'.'.$file_ext;
    $uploaded_image = "uploads/".$unique_image;
        
        if($productName == "" || $catId == "" || $brandId == "" || $body == "" || $price == "" || $type == "" ){
            
            $msg = "<span class='error'> Fields must not be empty</span>";
            return $msg;
        } 
         else{
             if(!empty( $file_name)){
         
                   if ($file_size >1048567) {
                     echo "<span class='error'>Image Size should be less then 1MB!
                  </span>";
                 } elseif (in_array($file_ext, $permited) === false) {
                     echo "<span class='error'>You can upload only:-"
                    .implode(', ', $permited)."</span>";
                  }

                else{
                    move_uploaded_file($file_temp, $uploaded_image);
                    
                    $query = "update tbl_product 
                             set 
                            productName   ='$productName',
                            catId         ='$catId',
                            brandId       ='$brandId',
                            body          ='$body',
                            price         ='$price',
                           image          ='$image',
                           type           ='$type'
                        
                            where productId='$id'";
                    
                     
                    $updated_row = $this->db->update($query);
                    
                    if($updated_row){
                        $msg = "<span class='success'>Product updated successfully!</span>";
                        return $msg;
                    }
                    else{
                        $msg = "<span class='error'>Product NOT updated !</span>";
                        return $msg;
                    }
                
                }
             }
             
                       else{
          
                             $query = "update tbl_product 
                             set 
                            productName   ='$productName',
                            catId         ='$catId',
                            brandId       ='$brandId',
                            body          ='$body',
                            price         ='$price',
                           type           ='$type'
                        
                            where productId='$id'";
                    
                    
                    $updated_row = $this->db->update($query);
                    
                    if($updated_row){
                        $msg = "<span class='success'>Product updated successfully!</span>";
                        return $msg;
                    }
                    else{
                        $msg = "<span class='error'>Product NOT updated !</span>";
                        return $msg;
                    }
             }
         }
    
    }
    
    public function delProById($id){
        
        $query = "select * from tbl_product where productId = $id";
        $getData = $this->db->select($query);
        if($getData){
            while($delImg = $getData->fetch_assoc()){
                $dellink = $delImg['image'];
                unlink($dellink);
            }
        }
         $delquery = "delete from tbl_product where productId = $id";
         $deldata = $this->db->delete($delquery);
        if($deldata){
                 $msg = "<span class='success'>Product deleted successfully!</span>";
                return $msg;
            }
            else{
                $msg = "<span class='error'>Product NOT deleted !</span>";
                return $msg;
            }
    }
    
    public function getFeaturedProduct(){
        
        $query = "select * from tbl_product where type='0' order by productId desc limit 4";
        $result = $this->db->select($query);
        return $result;
    }
    public function getNewProduct(){
        
        $query = "select * from tbl_product order by productId desc limit 4";
        $result = $this->db->select($query);
        return $result;
    }
     
    
    public function getSingleProduct($id){
        
        $query = "select p.*,c.catName,b.brandName 
                  from tbl_product as p,tbl_category as c,tbl_brand as b
                   where p.catId = c.catId AND p.brandId = b.brandId AND p.productId = '$id'";
                    
         $result = $this->db->select($query);
          return $result;
    }
     
    
    
}
    ?>