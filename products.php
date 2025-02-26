<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" href="data:," />
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>

   
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.min.css" />

    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.bootstrap.css" />
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap.js"></script>

   <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.4/css/responsive.dataTables.min.css" />

 <script src="https://cdn.datatables.net/responsive/3.0.4/js/dataTables.responsive.min.js"></script>

    <style>
        html,body{height:100%;}
        .required-field{position:relative;}
        .required-field::after{
            content:'*';
            color:red;
            position:absolute;
        }
        th{ text-align:start;}
        .table-wrapper,th,td{padding:0.5rem;}
        tbody tr td:first-child{font-size:14px;}
        .cell-border>* *{border-width:2px;}
        form{
            width:98%;
            margin:auto;
            border-radius:5px;
            background-color:blanchedalmond;
            padding:1rem 0;
            padding-left:5px;
        }
        .category,.description{width:50%;}
        @media only screen and (max-width:768px){
            .category,.description{width:100%;}
        }
    </style>
</head>
<body>

<h1>List of products</h1>

<?php
    require_once("./lib.php");
    $productsList = new Products("./test.xml");
    $productsList->form_control();
    $productsList->print_html_table_with_all_products();

?>
<?php $productsList->dataTable_init('products'); ?>
</body>

</html>