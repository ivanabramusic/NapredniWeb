<?php

    include('simple_html_dom.php'); // Uključi Simple HTML DOM biblioteku
    include_once 'DiplomskiRadovi.php';
    include_once 'db_connect.php';

    function fetchHTML($url) 
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0");
        $html = curl_exec($curl);
        if(curl_errno($curl)) {
            echo 'cURL Error: ' . curl_error($curl);
        }
        curl_close($curl);
        
        return $html;
    }

    $diplomskiRadovi = new DiplomskiRadovi($conn);

    for ($i = 2; $i <= 6; $i++) {
        $url = "https://stup.ferit.hr/index.php/zavrsni-radovi/page/$i/";
        $html_string = fetchHTML($url);

        // Parsiranje HTML-a pomoću DOMDocument i DOMXPath
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        $dom->loadHTML(mb_convert_encoding($html_string, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors(); 
        $xpath = new DOMXPath($dom);

        $radovi = [];
        foreach ($xpath->query('//li') as $li) {
            // Pronađi OIB iz URL-a slike
            $img = $xpath->query('.//img', $li);
            if ($img->length > 0) {
                preg_match('/(\d+)\.png$/', $img->item(0)->getAttribute('src'), $matches);
                $oib_tvrtke = $matches[1] ?? 'N/A';
            } else {
                continue;
            }

            // Pronađi naslov rada i link
            $link = $xpath->query('.//a[@class="fusion-rollover-link"]', $li);
            if ($link->length > 0) {
                $naslov = $link->item(0)->textContent;
                $link_rada = $link->item(0)->getAttribute('href');
            } else {
                continue;
            }


         // Dohvati HTML same stranice rada
            $tekst_rada_html = fetchHTML($link_rada);
            $tekst_dom = new DOMDocument();
            @$tekst_dom->loadHTML($tekst_rada_html);
            $tekst_xpath = new DOMXPath($tekst_dom);

            // Pronađi sve sekcije gdje je id = "content"
            $section = $tekst_xpath->query('//section[@id="content"]');
            $tekst_rada = "";

            if ($section->length > 0) {
                $paragrafi = $section->item(0)->getElementsByTagName('p');
            
                // Prolazimo kroz sve <p> tagove i dodajemo ih u tekst rada
                foreach ($paragrafi as $p) {
                    $tekst_rada .= trim($p->textContent) . "\n";
                }
            }
            
           
            if (empty(trim($tekst_rada))) {
                continue;
            }

            // Spremi podatke u niz
            $radovi[] = [
                'naziv_rada' => $naslov,       
                'tekst_rada' => $tekst_rada,   
                'link_rada' => $link_rada,     
                'oib_tvrtke' => $oib_tvrtke    
            ];
            
            }

            $diplomskiRadovi->save($radovi);

            $diplomskiRadovi->read();
          
                
        }


?>