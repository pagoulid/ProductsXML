<?php
class Products
{

    private $xml_file_path = '';
    private $website = '';

    public function __construct($xml_file_path = '',$website='mywebsite') // xml should have the same <website> tag
    {
        $this->xml_file_path = $xml_file_path;
        $this->website = $website;
    }

    /**
     * This function prints an HTML table with all the products as read from the xml file
     * @return void 
     */
    public function print_html_table_with_all_products()
    {
        //TODO 1:Θα πρέπει να συμπληρώσουμε την συνάρτηση ώστε να κάνει print το HTML table με τα προϊόντα του xml
        $xmldata = simplexml_load_file($this->xml_file_path) or die("Failed to load");
        $xml_data = $xmldata->children();
        $headers = $this->get_product_keys((array) $xml_data->PRODUCTS->PRODUCT[0]);
        //echo '<div class="table-wrapper">';
        echo '<table id = "products" class="table table-striped cell-border">';
            echo '<thead>';
                echo '<tr>';
                    foreach($headers as $hkey=>$header){
                        $class=($hkey==0)?'class="w-25"':'';
                        $colspan=($hkey==0)?'colspan="2"':'colspan="2"';

                        echo '<th '.$class.'>'.$header.'</th>';
                    }
                echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
            foreach ($xml_data->PRODUCTS->PRODUCT as $key => $prod) {    
                
                    $this->print_html_of_one_product_line($prod);
                
            }
            echo '</tbody>';
        echo '</table>';
        //echo '</div>';
        
    }

    /**
     * This function prints an HTML tr for a given product
     * @param mixed $prod It is the product object as retrieved from the xml file
     * @return void 
     */
    private function print_html_of_one_product_line($prod){
        //TODO 2: Θα πρέπει να συμπληρώσουμε τη συνάρτηση ώστε να κάνει print τα tr με τα στοιχεία του ενός προϊόντος
        //var_dump($prod);
        
        echo '<tr>';
        foreach($prod as $key=>$item){

            switch($key):

                case 'NAME':
                case 'BARCODE':
                case 'WEIGHT':
                    $item=$this->filter_CDATA($item);
                break;
                default:
                break;
            endswitch;
            echo '<td>'.$this->parse_content($item).'</td>';
            
        }
        echo '</tr>';
    }
    private function get_product_keys($prod){
        return array_keys($prod);

    }
    
   
    private function filter_CDATA($item){

        //print_r('CDATA content');
        $item = str_replace('<![CDATA[<?xml version="1.0" encoding="UTF-8" ?>', "", $item);
        $item = str_replace(']]>', "", $item);
        return $item;
    }
        

    private function parse_content($item){
        $content=null;
        $json = json_encode($item);
        $filtered_attr = json_decode($json,TRUE);
        $content = (gettype($filtered_attr) == 'array')?$filtered_attr[0]:$filtered_attr;
        return $content;

        
    }
public function form_control(){
    // /products.php
    echo '<form class="my-2" action="" method="post">';
        echo '<div class="row">';

            
            echo '<div class="form-group col-11 col-lg-4 m-auto m-lg-0">';
                    echo '<label for="newProduct" class="required-field">Product Name: </label>';
                    echo '<textarea class="form-control" id="newProduct" name="product_name" rows="3" required></textarea>';
                
            echo '</div>';

            echo '<div class="col-11 col-lg-8 m-auto m-lg-0">';
                echo '<div class="row">';
                    echo '<div class="col-6">';

                    echo '<div class="d-flex flex-column">';
                    echo '<div class="my-2">';
                        echo '<input type="number" step="0.01" class="form-control" name="product_price" placeholder="Price">';
                    echo '</div>';
                    echo '<div class="my-2">';
                        echo '<input type="number" class="form-control" name="product_quantity" placeholder="Quantity">';
                    echo '</div>';
                    echo '<div class="my-2">';
                        echo '<span class="d-inline-block category mb-1 mb-lg-0 px-0 px-lg-1"><input type="text" class="form-control" name="product_category" placeholder="Category"></span>';
                        
                        echo '<span class="d-inline-block description px-0 px-lg-2"><input type="text" class="form-control" name="product_category_desc" placeholder="Description"></span>';
                    echo '</div>';
                    echo '<div class="my-2">';
                        echo '<input type="text" class="form-control" name="product_manufacturer" placeholder="Manufacturer">';
                    echo '</div>';

                    echo '</div>';

                    echo '</div>';

                    echo '<div class="col-6 col-lg-5">';


                    echo '<div class="d-flex flex-column">';
                    echo '<div class="my-2">';
                        echo '<input type="number" class="form-control" name="product_barcode" placeholder="Barcode">';
                    echo '</div>';
                    echo '<div class="my-2">';
                        echo '<input type="number" step="0.1" class="form-control" name="product_weight" placeholder="Weight">';
                    echo '</div>';
                    echo '<div class="my-2">';
                        echo '<label for="inStock">In Stock </label>';
                        echo '<select id="inStock" class="form-control" name="product_instock">';
                            echo '<option selected>N</option>';
                            echo '<option>Y</option>';
                        echo '</select>';
                    echo '</div>';
                    echo '<div class="my-2">';
                        echo '<div class="form-check form-check-inline" >';
                            echo '<input class="form-check-input" type="checkbox" id="inlineCheckbox1"  name="product_available" value="available">';
                            echo '<label class="form-check-label" for="inlineCheckbox1">Αμεσα Διαθέσιμο</label>';
                        echo '</div>';
                        echo '<div class="form-check form-check-inline">';
                            echo '<input class="form-check-input" type="checkbox" id="inlineCheckbox2" name="product_available" value="not-available">';
                            echo '<label class="form-check-label" for="inlineCheckbox1">Εξαντλήθηκε</label>';
                        echo '</div>';
                    echo '</div>';

                    echo '</div>';
                    echo '</div>';

                echo '</div>';

                echo '<div class="my-2">';
                    echo '<button type="submit" class=" w-50 d-flex mx-auto justify-content-center btn btn-primary">Submit</button>';
                echo '</div>';
            echo '</div>';
        echo '</div>';
        
        
    echo '</form>';

    
    if(isset($_POST['product_name']) && $_POST['product_name']!='' ){
        $this->add_entry_to_xml();
        header("Location:".$_SERVER['REQUEST_URI']."");//Redirect after xml write, avoid duplicate data
        exit;
    }
    
    
    ?>
    <script>


    $("input:checkbox").click(function(){
        $("input:checkbox").prop("checked", false);
        $(this).prop("checked", true);

    })
    </script>
    
    <?php
}
public function dataTable_init($id){
    ?>
    <script>


    $(document).ready(function(){


        console.log('table init');
    
    var table =new DataTable('#<?php echo $id; ?>',{
        searchable:true,
        responsive:true,
        paging:true,
        pageLength:3,
        lengthMenu: [[3,5, 10, -1], [3,5, 10, "All"]],
        lengthChange: true,
        columns:[
            {data:'NAME'},
            {data:'PRICE'},
            {data:'QUANTITY'},
            {data:'CATEGORY'},
            {data:'MANUFACTURER'},
            {data:'BARCODE'},
            {data:'WEIGHT'},
            {data:'INSTOCK'},
            {data:'AVAILABILITY'},

        ],
        columnDefs:[
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: 1 },
            { responsivePriority: 2, targets: 2 },
            { responsivePriority: 5, targets: 3 },
            { responsivePriority: 4, targets: 4 },
            { responsivePriority: 4, targets: 5 },
            { responsivePriority: 4, targets: 6 },
            { responsivePriority: 3, targets: 7 },
            { responsivePriority: 4, targets: 8} 
            
        ]
    })


    })
      ;
   
    </script>
    <?php 
}

private function add_entry_to_xml(){ // call on submit


    $entry_name = $this->xml_POST_tag_value($_POST['product_name']);
    $entry_price = $this->xml_POST_tag_value($_POST['product_price']);
    $entry_quantity = $this->xml_POST_tag_value($_POST['product_quantity']);
    $entry_category = $this->xml_POST_tag_value($_POST['product_category']);
    $entry_category_desc = $this->xml_POST_tag_value($_POST['product_category_desc']);
    $entry_manufacturer = $this->xml_POST_tag_value($_POST['product_manufacturer']);
    $entry_barcode = $this->xml_POST_tag_value($_POST['product_barcode']);
    $entry_weight = $this->xml_POST_tag_value($_POST['product_weight'],'kg');
    $entry_instock = $this->xml_POST_tag_value($_POST['product_instock']);
    $entry_availability = $this->xml_POST_tag_checkbox_value($_POST['product_available'],['available'=>'Άμεσα Διαθέσιμο','not-available'=>'Έξαντλήθηκε']);

    $product_entry = [
        'NAME'=>['data'=>$entry_name,'CDATA'=>1],
        'PRICE'=>['data'=>$entry_price,'CDATA'=>0],
        'QUANTITY'=>['data'=>$entry_quantity,'CDATA'=>0],
        'CATEGORY'=>['data'=>$entry_category.'-&gt;'.$entry_category_desc,'CDATA'=>0],
        'MANUFACTURER'=>['data'=>$entry_manufacturer,'CDATA'=>0],
        'BARCODE'=>['data'=>$entry_barcode,'CDATA'=>1],
        'WEIGHT'=>['data'=>$entry_weight,'CDATA'=>1],
        'INSTOCK'=>['data'=>$entry_instock,'CDATA'=>0],
        'AVAILABILITY'=>['data'=>$entry_availability,'CDATA'=>0],
        
    ];

    $document = new DOMDocument();
    $document->load($this->xml_file_path);
    // Set the formatOutput property to true to pretty-print the XML
    $document->formatOutput = true;


    $document_entries= $document->getElementsByTagName('PRODUCT');
    $fentry = $document_entries->item(0);
    $new_entry = $document->createElement('PRODUCT', '');

    foreach($product_entry as $key=>$value){
        $attr=null;
       if($value['CDATA']){
        $attr = $document->createElement($key,'');
        $cdata = $document->createCDATASection($value['data']);
        $attr->appendChild($cdata);

       }
       else{
        $attr = $document->createElement($key, $value['data']);
       }
       
       $new_entry->appendChild($attr);
    }
    $fentry->parentNode->insertBefore($new_entry,$fentry);
    $document->save($this->xml_file_path);
    /* TEST */
}
private function write_attr($fp,$tag,$value){

    fwrite($fp,"            <".$tag.">\n");
    fwrite($fp,$value."\n");
    fwrite($fp,"            </".$tag.">\n");

}

private function xml_POST_tag_value($value,$postfix=''){ return ($value!='')?$value.$postfix:'-'; }
private function xml_POST_tag_checkbox_value($value,$options,$postfix=''){ 
    
    $rvalue='-'; // return value
    

    if(isset($value)){
        //print_r('post check');
        foreach($options as $okey => $option){

            if($value == $okey){
                //print_r('case');
                $rvalue = $option.$postfix;
                break;
            }
        }
    }
    return $rvalue;



}


}
