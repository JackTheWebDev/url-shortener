<?php

  require __DIR__ . '/vendor/autoload.php';

  $db = new Leaf\Db;

  $db = new Leaf\Db;

  $db->connect("localhost", "root", "admin", "urlshortener");



  app()->get("/", function() use($db){

    $id = request()->get("s");

    if(isset($id)){


        $response = $db->query('SELECT * FROM main')->where("id",$id)->fetchObj();

        header("Location: ".$response->url);

    }

    echo file_get_contents("templates/index.html");
  });


  // Shorten the url provided
  app()->post('/shorten',function() use($db){
    $url = request()->get("url");

    $response = $db->query('SELECT * FROM main')->where("url",$url)->fetchObj();

    if($response){
      response()->json([
        'status' => "success",
        'id' => $response->id
      ]);
      return;
    }



    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < 5; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    $data = [
      "url" => $url,
      "id" => $randomString
    ];

    $db->insert('main')->params($data)->execute();

    response()->json([
      'status' => "success",
      'id' => $randomString
    ]);

  });



  app()->run();
