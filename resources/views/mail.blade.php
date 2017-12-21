<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/public/css/font-awesome.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet">
    <title>Document</title>
</head>
<body>

<div class="container">
    <div class="up">
        <div class="date">
            {{ $data['date'] }}
        </div>
        <div class="twitter">
            <?php
                echo '<div class="twitter-url">'.$data['twitter'].'</div>';
            ?>
        </div>
    </div>
    <div class="middle">
        <?php
            echo '<img src="https://coindar.org'.$data['image'].'" alt="">'
        ?>
        <div class="name">
            <div class="subname">
                <div class="title">
                    {{ $data['caption']['title'] }}
                </div>
                <div class="tag">
                    {{ $data['caption']['tag'] }}
                </div>
            </div>
            <div class="subtag">
                {{ $data['caption']['subtag'] }}
            </div>
        </div>

    </div>
    <div class="content">
        <?php
            echo '<div class="text">'.$data['text'].'</div>';
        ?>
    </div>

</div>

<style>
    .container{
        width: 400px;
        height: auto;
        /*background: #e6e6e6;*/
        padding: 10px;
        border-radius: 3px;
        border: solid 1px #ccc;
    }
    .up{
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
    }
    .middle{
        width: 50%;
        display: flex;
        justify-content: space-between;
    }
    .subname{
        display: flex;
        justify-content: space-between;
    }
    .title{
        margin-right: 5px;
    }
    .name{
        margin-top: 10px;
    }
    .content{
        margin-top: 10px;
    }
    .down{
        margin-top: 10px;
        width: 25%;
        display: flex;
        justify-content: space-between;
    }
    .date{
        color: #929292;
    }
    a{
        color: #3e5ca7;
        text-decoration: none;
    }
    .ret, .fav{
        width: 50%;
        display: flex;
        justify-content: space-between;
    }
</style>




</body>
</html>