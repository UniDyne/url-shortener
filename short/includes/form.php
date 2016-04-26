<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous" />
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-hQpvDQiCJaD2H465dQfA717v7lu5qHWtDbWNPvaTJ0ID5xnPUlVXnKzq7b8YUkbN" crossorigin="anonymous" />	<title><?php echo PAGE_TITLE; ?></title>
</head>
<body onload="document.getElementById('longurl').focus()">
	<div class="container" style="width:750px;text-align:center;">
		<div class="row">
			<h1><?php echo PAGE_TITLE; ?></h1>
			
			<div class="col-md-12">
				<?php echo $msg; ?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				
				<form class="form" action="/short/" method="post">
					<div class="form-group">
						<label class="sr-only">Enter a long URL</label>
						<div class="input-group">
							<div class="input-group-addon"><i class="fa fa-globe"></i> URL</div>
							<input type="test" class="form-control" id="longurl" name="longurl" placeholder="http://www.my-website.com/something.html" />
							<span class="input-group-btn"><button type="submit" class="btn btn-primary">Shorten!</button></span>
						</div>
					</div>
					
				</form>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<p>
				By using nol.ag URL Shortener you agree to our <a href="policy.txt">terms of use</a>.
				</p>
			</div>
			<div class="col-md-12">
				<p>
				Please send email to <strong>abuse</strong> <em>at</em> <strong>nol</strong> <em>dot</em> <strong>ag</strong>
				to report abuse of this service. We take spam seriously and will do our best to respond quickly.
				</p>
			</div>
		</div>
	</div>
</body>
</html>
