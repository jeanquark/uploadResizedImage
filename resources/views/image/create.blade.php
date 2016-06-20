<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
    <div class="container">

        <div class="col-md-10 col-md-offset-1">
			
			@if ($message = Session::get('success'))
				<div class="alert alert-success alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
					{!! $message !!}
				</div>
				{{ Session::forget('success') }}
			@endif

			@if ($errors->any())        
				<div class='flash alert alert-danger alert-dismissable'> 
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>      
					@foreach ( $errors->all() as $error )               
						<p>{{ $error }}</p>         
					@endforeach     
				</div>  
			@endif
		
            {!! Form::open(array('route' => 'image.store', 'method' => 'POST', 'files'=> true )) !!}

                <h2>Upload an Image</h2>
            
                <div class="form-group well">
                    <div class="form-inline">
                        {!! Form::label('width', 'Choose image width in px') !!}    
                        {!! Form::text('width', Input::old('width'), array('class' => 'form-control', 'placeholder' => '900')) !!}&nbsp;
                        {!! Form::label('height', 'Choose image height in px') !!}
                        {!! Form::text('height', Input::old('height'), array('class' => 'form-control', 'placeholder' => '300')) !!}
                    </div>
                    <br/>
                    {!! Form::file('image') !!}
                    <br/>
                {!! Form::submit('Upload image', array('class'=>'btn btn-primary')) !!}
                </div>
                
            {!! Form::close() !!}
        </div>
    </div>
    
    <script src="//code.jquery.com/jquery-1.12.0.min.js"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</body>
</html>