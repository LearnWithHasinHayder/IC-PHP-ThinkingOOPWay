<?php 
class InvoiceBuilder{
    private $invoiceNumber;
    private $client;
    private $date;
    private $dueDate;
    private $items = [];
    private $discount;

    function setNumber($number){
        $this->invoiceNumber = $number;
        return $this;
    }

    function setClient($client){
        $this->client = $client;
        return $this;
    }

    function setDate($date){
        $this->date = $date;
        return $this;
    }

    function setDueDate($dueDate){
        $this->dueDate = $dueDate;
        return $this;
    }

    function addItem($item, $quantity, $price){
        $this->items[] = [
            'item' => $item,
            'quantity' => $quantity,
            'price' => $price
        ];
        return $this;
    }

    function setDiscount($discount){
        $this->discount = $discount;
        return $this;
    }

    private function getData(){
        return [
            'invoiceNumber' => $this->invoiceNumber,
            'client' => $this->client,
            'date' => $this->date,
            'dueDate' => $this->dueDate,
            'items' => $this->items,
            'discount' => $this->discount
        ];
    }

    function print(Printer $printer){
        $printer->print($this->getData());

        // echo "Invoice Number: {$this->invoiceNumber}\n";
        // echo "Client: {$this->client}\n";
        // echo "Date: {$this->date}\n";
        // echo "Due Date: {$this->dueDate}\n";
        // $subtotal = 0;
        // $total = 0;
        // foreach($this->items as $item){
        //     $subtotal += $item['quantity'] * $item['price'];
        //     echo "{$item['item']} - {$item['quantity']} x {$item['price']}\n";
        // }
        // echo "Sub Total: {$subtotal}\n";
        // if($this->discount){
        //     $discountAmount = $subtotal * $this->discount;
        //     echo "Discount: {$discountAmount}\n";
        //     $total = $subtotal - $discountAmount;
        // }else{
        //     $total = $subtotal;
        // }
        // echo "Total: {$total}\n";

    }
};

interface Printer{
    function print($invoiceData);
}

class ScreenPrinter implements Printer{
    function print($invoiceData){
        echo "Invoice Number: {$invoiceData['invoiceNumber']}\n";
        echo "Client: {$invoiceData['client']}\n";
        echo "Date: {$invoiceData['date']}\n";
        echo "Due Date: {$invoiceData['dueDate']}\n";
        $subtotal = 0;
        $total = 0;
        foreach($invoiceData['items'] as $item){
            $subtotal += $item['quantity'] * $item['price'];
            echo "{$item['item']} - {$item['quantity']} x {$item['price']}\n";
        }
        echo "Sub Total: {$subtotal}\n";
        if($invoiceData['discount']){
            $discountAmount = $subtotal * $invoiceData['discount'];
            echo "Discount: {$discountAmount}\n";
            $total = $subtotal - $discountAmount;
        }else{
            $total = $subtotal;
        }
        echo "Total: {$total}\n";
    }
}

class JSONPrinter implements Printer{
    function print($invoiceData){
        $jsonData = json_encode($invoiceData, JSON_PRETTY_PRINT);
        file_put_contents('invoice.json', $jsonData);
    }
}

class FilePrinter implements Printer{
    function print($invoiceData){
        $file = fopen('invoice.txt', 'w');
        fwrite($file, "Invoice Number: {$invoiceData['invoiceNumber']}\n");
        fwrite($file, "Client: {$invoiceData['client']}\n");
        fwrite($file, "Date: {$invoiceData['date']}\n");
        fwrite($file, "Due Date: {$invoiceData['dueDate']}\n");
        $subtotal = 0;
        $total = 0;
        foreach($invoiceData['items'] as $item){
            $subtotal += $item['quantity'] * $item['price'];
            fwrite($file, "{$item['item']} - {$item['quantity']} x {$item['price']}\n");
        }
        fwrite($file, "Sub Total: {$subtotal}\n");
        if($invoiceData['discount']){
            $discountAmount = $subtotal * $invoiceData['discount'];
            fwrite($file, "Discount: {$discountAmount}\n");
            $total = $subtotal - $discountAmount;
        }else{
            $total = $subtotal;
        }
        fwrite($file, "Total: {$total}\n");
        fclose($file);
    }
}

class HTMLPrinter implements Printer{
    function print($invoiceData){
        $html = "<html>";
        $html .= "<head><title>Invoice</title><link rel='stylesheet' href='https://matcha.mizu.sh/matcha.css'></head>";
        $html .= "<body>";
        $html .= "<h1>Invoice</h1>";
        $html .= "<p>Invoice Number: {$invoiceData['invoiceNumber']}</p>";
        $html .= "<p>Client: {$invoiceData['client']}</p>";
        $html .= "<p>Date: {$invoiceData['date']}</p>";
        $html .= "<p>Due Date: {$invoiceData['dueDate']}</p>";
        $html .= "<ul>";
        $subtotal = 0;
        $total = 0;
        foreach($invoiceData['items'] as $item){
            $subtotal += $item['quantity'] * $item['price'];
            $html .= "<li>{$item['item']} - {$item['quantity']} x {$item['price']}</li>";
        }
        $html .= "</ul>";

        //use table
        // $html .= "<table>";
        // $html .= "<tr><th>Item</th><th>Quantity</th><th>Price</th></tr>";
        // $subtotal = 0;
        // foreach($invoiceData['items'] as $item){
        //     $subtotal += $item['quantity'] * $item['price'];
        //     $html .= "<tr><td>{$item['item']}</td><td>{$item['quantity']}</td><td>{$item['price']}</td></tr>";
        // }
        // $html .= "</table>";
        $html .= "<p>Sub Total: {$subtotal}</p>";
        if($invoiceData['discount']){
            $discountAmount = $subtotal * $invoiceData['discount'];
            $html .= "<p>Discount: {$discountAmount}</p>";
            $total = $subtotal - $discountAmount;
        }else{
            $total = $subtotal;
        }
        $html .= "<p>Total: {$total}</p>";
        //add print call
        // $html .= "<button onclick='window.print()'>Print</button>";
        $html .= "</body>";
        $html .= "</html>";
        file_put_contents('invoice.html', $html);
    }
}

class PDFPrinter implements Printer{
    function print($invoiceData){
        $html = "<html>";
        $html .= "<head><title>Invoice</title><link rel='stylesheet' href='https://matcha.mizu.sh/matcha.css'></head>";
        $html .= "<body>";
        $html .= "<h1>Invoice</h1>";
        $html .= "<p>Invoice Number: {$invoiceData['invoiceNumber']}</p>";
        $html .= "<p>Client: {$invoiceData['client']}</p>";
        $html .= "<p>Date: {$invoiceData['date']}</p>";
        $html .= "<p>Due Date: {$invoiceData['dueDate']}</p>";
        $html .= "<ul>";
        $subtotal = 0;
        $total = 0;
        foreach($invoiceData['items'] as $item){
            $subtotal += $item['quantity'] * $item['price'];
            $html .= "<li>{$item['item']} - {$item['quantity']} x {$item['price']}</li>";
        }
        $html .= "</ul>";

        //use table
        // $html .= "<table>";
        // $html .= "<tr><th>Item</th><th>Quantity</th><th>Price</th></tr>";
        // $subtotal = 0;
        // foreach($invoiceData['items'] as $item){
        //     $subtotal += $item['quantity'] * $item['price'];
        //     $html .= "<tr><td>{$item['item']}</td><td>{$item['quantity']}</td><td>{$item['price']}</td></tr>";
        // }
        // $html .= "</table>";
        $html .= "<p>Sub Total: {$subtotal}</p>";
        if($invoiceData['discount']){
            $discountAmount = $subtotal * $invoiceData['discount'];
            $html .= "<p>Discount: {$discountAmount}</p>";
            $total = $subtotal - $discountAmount;
        }else{
            $total = $subtotal;
        }
        $html .= "<p>Total: {$total}</p>";
        //add print call
        $html .= "<script>window.print()</script>";
        $html .= "</body>";
        $html .= "</html>";
        file_put_contents('invoice.html', $html);
    }
}

$screenPrinter = new ScreenPrinter;
$jsonPrinter = new JSONPrinter;
$filePrinter = new FilePrinter;
$HTMLPrinter = new HTMLPrinter;
$PDFPrinter = new PDFPrinter;



$invoice = (new InvoiceBuilder)
->setNumber('INV-001')
->setClient('John Doe')
->setDate('2020-01-01')
->setDueDate('2020-01-31')
->addItem('Apple', 1, 10)
->addItem('Banana', 2, 20)
->addItem('Cherry', 3, 30)
->setDiscount(0.1)
// ->print($screenPrinter);
// ->print($jsonPrinter);
// ->print($filePrinter);
// ->print($HTMLPrinter);
->print($PDFPrinter);