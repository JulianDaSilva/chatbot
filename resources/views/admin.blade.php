@inject('BotManController', 'App\Http\Controllers\BotManController')
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>BotMan Admin</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        body {
            font-family: "Source Sans Pro", sans-serif;
            margin: 0;
            padding: 0;
            background: radial-gradient(#57bfc7, #45a6b3);
        }

        .container {
            display: flex;
            height: 100vh;
            align-items: center;
            justify-content: center;
        }

        .content {
            text-align: center;
        }

        .left-div, .right-div {
            width: 50%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .left-div>form, .right-div>form {
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .left-div>form>input, .left-div>form>textarea, .left-div>form>select, .right-div>form>input, .right-div>form>textarea, .right-div>form>select {
            width: 50%;
            margin-bottom: 20px;
            border-radius: .25rem;
            border: 1px solid #ced4da;
            padding: .375rem .75rem;
            font-family: 'MS Sans Serif';
        }

        div>form>input[type=button] {
            background-color: #008CBA; /* Green */
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            transition: 0.4s;
        }
        input[type=button]:hover {
            background-color: #076D8F;
            transition: 0.4s;
        }
        div>form>.infon {
            color: green;
        }
        .hidden {
            display: none;
        }
    </style>
    <script>
        function changeQuestion() {
            document.getElementById('keywordQ').value = '';
            document.getElementById('reponseQ').value = '';
        }

        function addAnswer() {
            item = document.getElementById('listNewQuestion').value;
            $.getJSON("../askAdmin.json", function(json) {
                viewData = "{{ $BotManController::toJson($ask) }}";
                indexDel = json.indexOf(item);
                removed = json.splice(indexDel, 1);
                console.log(json);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'POST',
                    url:'/botman/admin',
                    data:{data: json, file: 'askAdmin.json', viewdata: viewData},
                    success:function(data){
                        tasks = '';
                        data.success.forEach(element => { 
                            tasks = tasks + "<option value=" + element + ">" + element + "</option>"
                        });
                        console.log(tasks);
                        document.getElementById('listNewQuestion').innerHTML = tasks;
                        document.getElementById('keywordQ').value = '';
                        document.getElementById('reponseQ').value = '';
                        document.getElementById('infon').classList.remove("hidden");
                        
                    }
                }); 
            });
            
            $.getJSON("../question.json", function(json) {
                keyword = document.getElementById('keywordQ').value;
                reponse = document.getElementById('reponseQ').value;
                json.push([keyword, reponse]);
                jsonfile = JSON.stringify(json);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'POST',
                    url:'/botman/admin',
                    data:{data: jsonfile, file: 'question.json'},
                    success:function(data){
                        // console.log(data);
                        
                    }
                }); 
            });   
        }

        function addAnswerE() {
            $.getJSON("../question.json", function(json) {
                keyword = document.getElementById('keywordE').value;
                reponse = document.getElementById('reponseE').value;
                data_id = document.getElementById('keywordE').getAttribute('data-id');
                json.forEach(element => { if (element[0] == data_id) {
                    element[0] = keyword;
                    element[1] = reponse;
                }});
                jsonfile = JSON.stringify(json);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type:'POST',
                    url:'/botman/admin',
                    data:{data: jsonfile, file: 'question.json'},
                    success:function(data){
                    }
                }); 
            });
        }

        function initForm() {
            valueExist = document.getElementById('listExist').value
            document.getElementById('keywordE').value = valueExist;
            document.getElementById('keywordE').setAttribute('data-id', valueExist);
            $('.searchAnswer').each(function(i, obj) {
                if (obj.id == valueExist) {
                    document.getElementById('reponseE').value = obj.value;
                }
            });
        }
    </script>
</head>
<body onLoad="initForm()">
<div class="container">
    <div class="left-div">
        <form action="/botman/admin" method="get">
            @method('PUT')
            <h2>Nouvelle question</h2>
            <select onChange="changeQuestion()" name="newQuestion" id="listNewQuestion">
                @foreach ($ask as $element)
                    <option value="{{ $element }}">{{ $element }}</option>
                @endforeach
            </select>
            <textarea placeholder="Mots clés" name="keyword" id="keywordQ" cols="30" rows="10"></textarea>
            <textarea placeholder="Réponse" name="reponse" id="reponseQ" cols="30" rows="10"></textarea>
            <input onClick="addAnswer()" type="button" value="Enregistrer">
            <p class="infon hidden" id="infon">La nouvelle réponse a était ajouté !</p>
        </form>
    </div>
    <div class="right-div">
        <form action="/botman/admin" method="get">
            @method('PUT')
            <h2>Existant</h2>
            <select onChange="initForm()" name="newQuestion" id="listExist">
                @foreach ($exist as $element)
                    <option value="{{ $element }}">{{ $element }}</option>
                @endforeach
            </select>
            @foreach ($existAnswer as $key => $node)
                <input type="text" id="{{ $key }}" value="{{ $node }}" style="display: none;" class="searchAnswer">
            @endforeach
            <textarea data-id="" class="" name="keyword" id="keywordE" cols="30" rows="10"></textarea>
            <textarea name="reponse" id="reponseE" cols="30" rows="10"></textarea>
            <input onClick="addAnswerE()" type="button" value="Enregistrer">
        </form>
    </div>
    <form action="/" method="POST">
        @method('PUT')
        @csrf
        <input type="submit" value="Se déconnecter">
    </form>
</div>

<script src="/js/app.js"></script>
</body>
</html>