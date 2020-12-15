<?php
include('simple_html_dom.php');
$teks_asli = $_GET['q'];
$hasil = str_replace([' '], ['-'], $teks_asli);
$url='https://www.google.com/search?lr=lang_id&safe=strict&biw=1366&bih=625&tbs=srcf%3AH4sIAAAAAAAAANOuzC8tKU1K1UvOz1VLS0xOTcrPzwZzsvNzCxKLwcyS_1Oz8gtSUzEQwLzOvOLWoJCezDKKpJDUFTAMA9p-Ed0oAAAA%2Ccc%3A1%2Clr%3Alang_1id&tbm=vid&sxsrf=ALeKk03DkE2RgASZkdQDkaAXEohjI-xEeg%3A1607933402240&ei=2h3XX72RDueQ4-EPivOQ4AQ&q=site%3A%22youtube.com%3Fwatch%3D%22+'.$hasil.'&oq=site%3A%22youtube.com%3Fwatch%3D%22+'.$hasil.'&gs_l=psy-ab.3...20705.23125.0.23465.10.10.0.0.0.0.330.1310.0j6j0j1.7.0....0...1c.1.64.psy-ab..3.0.0....0.Gve1ALPae5E';
// Create DOM from URL or file
$html = file_get_html($url);


$videos = [];


$i = 1;
foreach ($html->find('div[class=ZINbbc xpd O9g5cc uUPGi]') as $video) {
        if ($i > 10) {
                break;
        }


        $videoDetails = $video->find('div.kCrYT', 0);


        $videoTitle = str_replace(' - YouTube','', $videoDetails->find('h3', 0)->plaintext);


        $videoUrl = urldecode($videoDetails->find('a', 0)->href);
        $videUrl = explode("=",$videoUrl);
        $videoUrl = str_replace("&sa", "", $videUrl[2]);
        $videoDatee = $video->find('div[class=BNeawe s3v9rd AP7Wnd]', 0);
        $videoDate = $videoDatee->find('span', 0)->plaintext;

        $videos[] = [
                'title' => $videoTitle,
                'url' => $videoUrl,
                'date' => $videoDate
        ];

        $i++;
}

echo '{ "status" : "success", "items" :' . json_encode($videos, JSON_UNESCAPED_UNICODE) . '}';

//echo $html;
?>
