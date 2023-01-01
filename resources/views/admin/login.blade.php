<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <style>
        body{
            background-color: black;
        }
        div{
            width: 30%;
            height: 70%;
            text-align: center;
            margin-left:  35%;
        }
        input,h1,p,button,label{
            width: 100%;
            border: 0;

            margin-left: 0;
        }
        label{
            text-align: left;
            float: left;
            height: 90px;
            color: #7f7b73;
            overflow: hidden;
            font-size: 10px;
        }
        input{
            height: 50px;
            background-color: #1a1a1a;
            padding-left: 20px;
            max-width: 100%;
            font-size: 10px;
            margin-top: 5px;
            color: white;
        }
        h1{
            color: #efefef ;
        }
        p{
            color: #7f7b73;
            margin-bottom: 40px;
        }
        .b1{
            color: black;
            background-color: #efefef ;
            height: 50px;
            font-size: 17px;
            margin-bottom: 50px;
            font-weight: bold;
        }
        .b2{
            margin-bottom: 30px;
            height: 50px;
            background-color: black;
            border: 1px solid #efefef;
            color: #efefef;
            font-size: 17px;
            letter-spacing: 3px;
        }
        a{
            text-decoration: none;
            color: #efefef;
        }
        .l , .in1{
            position:relative;
        }
        span{
            display:inline-block;
            position: absolute;
            top: 32px;
            right: 0;
            margin-right: 20px;

        }
        span a{
            color: #7f7b73;
            text-transform: uppercase;
        }

    </style>
</head>
<body>

<div>
    <h1>admin login</h1>
    <p>we miss you!</p>
    @if($errors->any())
        <p>{{ $errors->first()  }}</p>
    @endif
    <form action="{{ route('admin.login') }}" method="post">
        @csrf
        <label>your email<input name="email" placeholder="email" type="email"></label>
        <label class="l" >your password<input name="password" class="in1" placeholder="password" type="password"></label>


        <button class="b1" type="submit">login now</button>
    </form>
</div>




</body>
</html>
