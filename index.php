<?php
      error_reporting(error_reporting() & ~E_NOTICE);
      header('Content-Type: application/javascript');
function bacaHTML($url){

    // inisialisasi CURL
    $data = curl_init();

    // setting CURL
    curl_setopt($data, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($data, CURLOPT_URL, $url);

    // menjalankan CURL untuk membaca isi file
    $hasil = curl_exec($data);
    curl_close($data);
    return $hasil;
}

//mengambil data dari kompas


     $bacaHTML = bacaHTML('https://downloadlagugo.com/unduh/'.$_GET['url'].'');
$bacaHTML = str_replace("https://y-api.org/button/?v=", "", $bacaHTML);
$bacaHTML = str_replace("&f=mp3&bc=#0EAA4C", "", $bacaHTML);
//membuat dom dokumen
$dom = new DomDocument();

//mengambil html dari kompas untuk di parse
@$dom->loadHTML($bacaHTML);

//nama class yang akan dicari
$classname="download noleft";

//mencari class memakai dom query
$finder = new DomXPath($dom);
$spaner = $finder->query("//*[contains(@class, '$classname')]");

//mengambil data dari class yang pertama
$span = $spaner->item(0);
//dari class pertama mengambil 2 elemen yaitu a yang menyimpan judul dan link dan span yang menyimpan tanggal
$linkkk =  $span->getElementsByTagName('iframe');
$linkk =  $span->getElementsByTagName('a');                                            ?>
document.getElementsByName("var-1")[0].value = "<?php echo $linkkk->item(0)->getAttribute('src'); ?>" ;
document.getElementsByName("var-2")[0].value = "<?php echo $linkk->item(0)->getAttribute('href'); ?>" ;




<?php $bacaHTML = bacaHTML('https://downloadlagugo.com/'.$_GET['url'].'.html');
$bacaHTML = str_replace(" MB (", " MB</strong><strong>(", $bacaHTML);
//membuat dom dokumen
$dom = new DomDocument();

//mengambil html dari kompas untuk di parse
@$dom->loadHTML($bacaHTML);

//nama class yang akan dicari
$classname="info noleft";

//mencari class memakai dom query
$finder = new DomXPath($dom);
$spaner = $finder->query("//*[contains(@class, '$classname')]");

//mengambil data dari class yang pertama
$span = $spaner->item(0);

//dari class pertama mengambil 2 elemen yaitu a yang menyimpan judul dan link dan span yang menyimpan tanggal
$link =  $span->getElementsByTagName('strong');

     ?>
 document.getElementsByName("blog_title")[0].value = "<?php echo $link->item(1)->nodeValue; ?> - <?php echo $link->item(0)->nodeValue; ?>[<?php echo $linkkk->item(0)->getAttribute('src'); ?>]" ;
 document.getElementsByName("blog_cat")[0].value = "<?php if (preg_match("/Download/i", $link->item(3)->nodeValue)) {
    echo str_replace("Download Lagu ", "", $link->item(3)->nodeValue);
} else {
    echo str_replace("Download Lagu ", "", $link->item(2)->nodeValue);
}
?>" ;
document.getElementsByName("var-3")[0].value = "<?php if (preg_match("/MB/i", $link->item(5)->nodeValue)) {
    echo $link->item(5)->nodeValue;
} else {
    echo $link->item(4)->nodeValue;
}
?>" ;
 document.getElementsByName("var-4")[0].value = "<?php if (preg_match("/menit/i", $link->item(7)->nodeValue)) {
    echo str_replace(' menit', '', $link->item(7)->nodeValue);
} else {
    echo str_replace(' menit', '', $link->item(6)->nodeValue);
}
?>" ;
 document.getElementsByName("var-5")[0].value = "<?php echo $link->item(1)->nodeValue; ?>" ;
