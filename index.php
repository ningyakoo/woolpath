	<?php 
		session_start();
		require "header.php";
		include "connection.php";
		
		
		if(isset($_POST['submit']))
		{
			$user = $_POST['user'];
			$pass = $_POST['pass'];
			
			$sql = "SELECT * FROM users WHERE user='".$user."' AND pass='".$pass."'";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()) {
					$_SESSION['user'] = $user;
					$_SESSION['pass'] = $pass;
					$_SESSION['id'] = $row['id'];
				}
			} else {
				echo "<script>alert('Usuario y/o contraseña incorrecta.')</script>";
			}
		}
		
		
	
	?>
	
	<style>
		body
		{
			background: url(images/fondo1.jpg) no-repeat fixed center;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
			font-family: 'Luckiest Guy', cursive;
		}
	
		.canvasHolder
		{
			z-index: 1;
			position: absolute;
			top: 50%; 
			left: 50%; 
			width: 1280px; 
			height: 720px; 
			margin-top: -360px; 
			margin-left: -620px; 
		}
	
		#canvas
		{
			background-color: #FFF;
			border: 2px dotted #000;
			border-radius: 15px;
			
		}
		
		#tabla
		{
			z-index: 99;
			display: none;
			position: relative;
		}
		
		.tab
		{
			/*position: absolute;
			top: 350px;
			left: 650px;*/
			margin-left: 70%;
			margin-top: 35%;
		}
		
		.tab label
		{
			font-size: 20px;
			color: #FEFA83;
			-webkit-text-stroke-width: 1.4px;
			-webkit-text-stroke-color: #492C00;
		}
		
		.tab input[type='text']
		{
			font-family: 'Luckiest Guy', cursive; 
			font-size: 17px;
			padding: 10px;
			border: 2px solid #BCBCBC;
			border-radius: 4px;
			background-color: #DDC2AA;
			color: black;
			width: 300px;
		}
		
		.btn
		{
			text-align: right;
		}
		
		.tab input[type='submit']
		{
			font-family: 'Luckiest Guy', cursive; 
			font-size: 20px;
			color: #FEFA83;
			background: url(images/madera.jpg) no-repeat fixed center;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;
			padding: 15px;
			width: 100px;
			border: 2px solid black;
			border-radius: 8px;
			
			
		}
		
		.tab input[type='submit']:hover
		{
			cursor: pointer;	
			color: #e3c65f;
		}
		
		#highscore
		{
			z-index: 99;
			display: none;
			position: relative;
			width: 1100px;
			margin-left: 46%;
			margin-top: 27%;
		}
		
		#highscore .spnormal h2
		{
			color: #ECDD6F;
			-webkit-text-stroke-width: 1px;
			-webkit-text-stroke-color: #000;	
		}
		
		#highscore .sphardcore h2
		{
			color: #F76262;
			-webkit-text-stroke-width: 1px;
			-webkit-text-stroke-color: #000;	
		}
		
		.spnormal
		{
			width: 45%;
			padding: 10px;
			border: .5rem dotted #C8B829;
			background-clip: padding-box;
			border-radius: 1.3rem;
			float: left;
			margin-right: 15px;
		}
		
		#highscore td
		{
			width: 150px;
			text-align: center;
		}
		
		.sphardcore
		{
			width: 45%;
			padding: 10px;
			border: .5rem dotted #F76262;
			background-clip: padding-box;
			border-radius: 1.3rem;
			float: left;
			margin-left: 15px;
		}
		
	</style>
	
	<script>
	var stage, screenWidth, screenHeight;
	var loader, sky;
	var titulo, cuerda, versus, cooperativo;
	var tx1, tx2, tx3, tx4, tx5;
	var tz1;
	var singlePlayer, normalMode, hardcore, back;
	var highScore;
	var menu = [], modesSP = [], modesMP = [], modesV = [];
	var sB1, sB2;
	var mode;
	var usuario;
	
	manifest = [
			{src: "sky.png", id: "sky"},
			{src: "titulo.png", id: "titulo"},
			{src: "cuerda.png", id: "cuerda"},
			{src: "singlePlayer.png", id: "SP"},
			{src: "spikedBall.png", id: "spikeB"},
			{src: "login.png", id: "login"},
			{src: "cross.png", id: "cross"},
		];
	
	function init()
	{
		examples.showDistractor();	
		stage = new createjs.Stage("canvas");
		screenWidth = stage.canvas.width;
		screenHeight = stage.canvas.height; 
		
		loader = new createjs.LoadQueue(false);
		loader.addEventListener("complete", handleComplete);
		loader.loadManifest(manifest, true, "images/");
	}
	
	function handleComplete()
	{
		examples.hideDistractor();
		
		sky = new createjs.Shape();
		sky.graphics.beginBitmapFill(loader.getResult("sky")).drawRect(0, 0, screenWidth, screenHeight);
		stage.addChild(sky);
		
		mode = 'sp';	
		
		var datos = {xcont: 240, ycont: 150, wcont: 800, hcont: 450, xcross: 755, ycross: 38};
		
		var container = createContainer(datos);
		
		var imgLogin = new createjs.Bitmap(loader.getResult("login"));
		imgLogin.x = 60;
		imgLogin.y = 60;
		imgLogin.regX = loader.getResult("login").width/2;
		imgLogin.regY = loader.getResult("login").height/2;
		imgLogin.scaleX = imgLogin.scaleY = 0.5;
		
		imgLogin.addEventListener("mouseover", function()
		{
			$("body").css("cursor", "pointer");
			imgLogin.scaleX = imgLogin.scaleY = 0.6;
		});

		imgLogin.addEventListener("mouseout", function()
		{
			$("body").css("cursor", "default");
			imgLogin.scaleX = imgLogin.scaleY = 0.5;
		});

		imgLogin.addEventListener("click", function()
		{
			stage.addChild(container);
			switch(mode)
			{
				case 'sp':
					stage.removeChild(tx1);
					stage.removeChild(singleplayer);
					break;
				case 'sp-modes':
					stage.removeChild(tx2, tx3, tx5);
					stage.removeChild(normalMode, hardcore, back);
					break;
			}
			$("#tabla").css("display", "block");
			
		});
		
		var login = new createjs.Text("login", "28px Luckiest Guy", "#6176ab");
		login.x = 120;
		login.y = 45;
		
		titulo = new createjs.Bitmap(loader.getResult("titulo"));
		titulo.shadow = new createjs.Shadow("#454", 1, 2, 3);
		titulo.x = 615;
		titulo.y = 160;
		titulo.scaleX = titulo.scaleY = 1;
		titulo.regX = loader.getResult("titulo").width/2;
		titulo.regY = loader.getResult("titulo").height/2;
		titulo.direction = 'l';
		titulo.rotation = 0;
		
		cuerda = new createjs.Bitmap(loader.getResult("cuerda"));
		cuerda.shadow = new createjs.Shadow("#454", 1, 2, 3);
		cuerda.x = 615;
		cuerda.y = 50;
		cuerda.scaleX = cuerda.scaleY = 0.6;
		cuerda.regX = loader.getResult("cuerda").width/2;
		cuerda.regY = loader.getResult("cuerda").height/2;
		
		tx1 = new createjs.Text("Single Player", "36px Luckiest Guy", "#000");
		tx1.outline = 4;
		tx1.x = 500;
		tx1.y = 320;		
		singleplayer = tx1.clone();
		singleplayer.outline = false;
		singleplayer.x = 500
		singleplayer.y = 320
		singleplayer.color = "#FDB12E";
		menu.push(tx1);
		menu.push(singleplayer);
		
		/**********************ModesSP***************************/
			
		/** Normal Mode **/		
		tx2 = new createjs.Text("Normal Mode", "36px Luckiest Guy", "#000");
		tx2.outline = 4;
		tx2.x = 500;
		tx2.y = 320;
		normalMode = tx2.clone();
		normalMode.outline = false;
		normalMode.x = 500
		normalMode.y = 320
		normalMode.color = "#FFA42D";
		
		modesSP.push(tx2);
		modesSP.push(normalMode);		
		/*********************/
		
		/** HARDCORE Mode **/
		tx3 = new createjs.Text("Hardcore", "36px Luckiest Guy", "#000");
		tx3.outline = 4;
		tx3.x = 530;
		tx3.y = 380;
		hardcore = tx3.clone();
		hardcore.outline = false;
		hardcore.x = 530
		hardcore.y = 380
		hardcore.color = "#F2641D";
		
		modesSP.push(tx3);
		modesSP.push(hardcore);
		/*********************/
		
		/** BACK **/
		tx5 = new createjs.Text("Atrás", "30px Luckiest Guy", "#000");
		tx5.outline = 4;
		tx5.x = 565;
		tx5.y = 450;
		back = tx5.clone();
		back.outline = false;
		back.x = 565;
		back.y = 450;
		back.color = "#FFA42D";
		
		modesSP.push(tx5);
		modesSP.push(back);
		/*********************/
		
		/********************************************************/
		
		/**********************HighScore*************************/
		
		tz1 = new createjs.Text("High Score", "36px Luckiest Guy", "#000");
		tz1.outline = 4;
		tz1.x = 524;
		tz1.y = 450;		
		highScore = tz1.clone();
		highScore.outline = false;
		highScore.x = 524
		highScore.y = 450
		highScore.color = "#D2DB6D";
		menu.push(tz1);
		menu.push(highScore);
		
		/********************************************************/
		
		sB1 = new createjs.Bitmap(loader.getResult("spikeB"));
		sB1.regX = loader.getResult("spikeB").width/2;
		sB1.regY = loader.getResult("spikeB").height/2;
		sB2 = sB1.clone();

		/* Eventos */
		
		/** Evt SP **/

		singleplayer.addEventListener("mouseover", function()
		{
			$("body").css("cursor", "pointer");
			stage.addChild(sB1, sB2);
			sB1.x = singleplayer.x-75;
			sB1.y = singleplayer.y+23;
			sB2.x = singleplayer.x+320;
			sB2.y = singleplayer.y+23;
			singleplayer.color = "#FFA42D";
			tx1.outline = 5;
		});

		singleplayer.addEventListener("mouseout", function()
		{
			$("body").css("cursor", "default");
			stage.removeChild(sB1, sB2);
			singleplayer.color = "#FDB12E";
			tx1.outline = 4;
		});

		singleplayer.addEventListener("click", function()
		{
			for(var i in menu)
			{
				stage.removeChild(menu[i]);
			}
			
			for(var i in modesSP)
			{
				stage.addChild(modesSP[i]);
			}
			stage.removeChild(sB1, sB2);
			mode = 'sp-modes';
			
		});
		
		/************************/
		
		/** Evt HS **/
		
		highScore.addEventListener("mouseover", function()
		{
			$("body").css("cursor", "pointer");
			stage.addChild(sB1, sB2);
			sB1.x = highScore.x-75;
			sB1.y = highScore.y+23;
			sB2.x = highScore.x+320;
			sB2.y = highScore.y+23;
			highScore.color = "#E9FC0F";
			tz1.outline = 5;
		});

		highScore.addEventListener("mouseout", function()
		{
			$("body").css("cursor", "default");
			stage.removeChild(sB1, sB2);
			highScore.color = "#D2DB6D";
			tz1.outline = 4;
		});

		highScore.addEventListener("click", function()
		{
			for(var i in menu)
			{
				stage.removeChild(menu[i]);
			}
			
			stage.removeChild(sB1, sB2);
			$("#highscore").css("display", "block");
			
			mode = 'sp';
			
			var datos = {xcont: 40, ycont: 50, wcont: 1200, hcont: 640, xcross: 1155, ycross: 38};
			stage.addChild(createContainer(datos));
			
		});
		
		/***********************/
		
		/** Evt Normal Mode **/
		
		normalMode.addEventListener("mouseover", function()
		{
			$("body").css("cursor", "pointer");
			stage.addChild(sB1, sB2);
			sB1.x = normalMode.x-75;
			sB1.y = normalMode.y+23;
			sB2.x = normalMode.x+320;
			sB2.y = normalMode.y+23;
			normalMode.color = "#FFA42D";
			tx2.outline = 5;
		});

		normalMode.addEventListener("mouseout", function()
		{
			$("body").css("cursor", "default");
			stage.removeChild(sB1, sB2);
			normalMode.color = "#FDB12E";
			tx2.outline = 4;
		});

		normalMode.addEventListener("click", function()
		{
			cargarDatos(1, 1);
		});
		
		/************************/
		
		/** Evt HARDCORE **/
		
		hardcore.addEventListener("mouseover", function()
		{
			$("body").css("cursor", "pointer");
			stage.addChild(sB1, sB2);
			sB1.x = hardcore.x-75;
			sB1.y = hardcore.y+20;
			sB2.x = hardcore.x+240;
			sB2.y = hardcore.y+20;
			hardcore.color = "#F5360C";
			tx3.outline = 5;
		});

		hardcore.addEventListener("mouseout", function()
		{
			$("body").css("cursor", "default");
			stage.removeChild(sB1, sB2);
			hardcore.color = "#F2641D";
			tx3.outline = 4;
		});

		hardcore.addEventListener("click", function()
		{
			cargarDatos(1, 2);			
		});
		
		
		/*****************/
		
		/** Evt Back **/
		
		back.addEventListener("mouseover", function()
		{
			$("body").css("cursor", "pointer");
			stage.addChild(sB1, sB2);
			sB1.x = back.x-75;
			sB1.y = back.y+20;
			sB2.x = back.x+170;
			sB2.y = back.y+20;
			back.color = "#FFA42D";
			tx5.outline = 5;
		});

		back.addEventListener("mouseout", function()
		{
			$("body").css("cursor", "default");
			stage.removeChild(sB1, sB2);
			back.color = "#FDB12E";
			tx5.outline = 4;
		});

		back.addEventListener("click", function()
		{
			for(var i in menu)
			{
				stage.addChild(menu[i]);
			}
			
			for(var i in modesSP)
			{
				stage.removeChild(modesSP[i]);
			}
			stage.removeChild(sB1, sB2);
			mode = 'sp';
		});
		/******************/

		
		/* Mostrar elementos principales del menú */
		stage.addChild(sky, cuerda, titulo, tx1, singleplayer, tz1, highScore, imgLogin, login);
		
		if(usuario != null){
			usuario.x = screenWidth - (usuario.text.length)-140;
			usuario.y = 20;
			stage.addChild(usuario);
		}
		
		createjs.Touch.enable(stage);
		stage.enableMouseOver(60);
		createjs.Ticker.setFPS(60);
		/*stage.addEventListener("stagemousedown", downMouse);
		stage.addEventListener("stagemouseup", upMouse);*/			
		createjs.Ticker.addEventListener("tick", tick);
	}
	
	function createContainer(datos) {
		var container = new createjs.Container();
		/** Contenedor **/
		var bg = new createjs.Shape();
		bg.graphics.beginFill('#F4F0C4').beginStroke("#000").setStrokeStyle(2).drawRoundRect(0, 0, datos.wcont, datos.hcont, 10, 10, 10, 10).endFill();
		bg.alpha = 0.96;
		/*****************/
		container.x = datos.xcont;
		container.y = datos.ycont;
		/** Elementos **/
		var cross = new createjs.Bitmap(loader.getResult("cross"));
		cross.x = datos.xcross;
		cross.y = datos.ycross;
		cross.scaleX = cross.scaleY = 0.7;
		cross.regX = loader.getResult("cross").width/2;
		cross.regY = loader.getResult("cross").height/2;
		cross.addEventListener("mouseover", function()
		{
			$("body").css("cursor", "pointer");
			cross.scaleX = cross.scaleY = 0.6;
		});

		cross.addEventListener("mouseout", function()
		{
			$("body").css("cursor", "default");
			cross.scaleX = cross.scaleY = 0.7;
		});

		cross.addEventListener("click", function()
		{
			$("#tabla").css("display", "none");
			$("#highscore").css("display", "none");
			stage.removeChild(container);
			switch(mode)
			{
				case 'sp':
					stage.addChild(tx1, singleplayer, tz1, highScore);
					break;
				case 'sp-modes':
					stage.addChild(tx2, tx3, tx5);
					stage.addChild(normalMode, hardcore, back);
					break;
			}
			
			/*stage.addChild(tx1, tx2, tx3, tx5);
			stage.addChild(singleplayer, normalMode, hardcore, back);*/
		});
		/****************/
		
		container.addChild(bg, cross);
		return container;
	}
	
	function cargarDatos(m1, m2)
	{
		// Creamos el formulario auxiliar
		var form = document.createElement("form");

		// Le añadimos atributos como el name, action y el method
		form.setAttribute("name", "formulario");
		form.setAttribute("action", "game.php");
		form.setAttribute("method", "post");

		// Creamos un input para enviar el valor
		var input = document.createElement("input");
		var input2 = document.createElement("input");

		//input1
		input.setAttribute("name", "m1");
		input.setAttribute("type", "hidden");
		input.setAttribute("value", m1);
		
		//input2
		input2.setAttribute("name", "m2");
		input2.setAttribute("type", "hidden");
		input2.setAttribute("value", m2);		

		// Añadimos el input al formulario
		form.appendChild(input);
		form.appendChild(input2);

		// Añadimos el formulario al documento
		document.getElementsByTagName("body")[0].appendChild(form);
		
		// Hacemos submit
		document.formulario.submit();
	}
	
	function mostrarUsuario(user)
	{
		usuario = new createjs.Text(user, "24px Luckiest Guy", "#000");
		
	}
	
	function tick(evt)
	{
		if(titulo.direction == 'l'){
			titulo.rotation += 0.2;
			cuerda.rotation += 0.07;
		}else{
			titulo.rotation -= 0.2;
			cuerda.rotation -= 0.07;
		}
		if(titulo.rotation >= 4)
			titulo.direction = 'r';
		else if(titulo.rotation <= -4)
			titulo.direction = 'l';
			
		sB1.rotation += 5;
		sB2.rotation += 5;
		
		stage.update();
	}
	
	
	</script>
	
</head>

<body onload="init()">
<?php
	//echo "<script>init()</script>";
	
	if(isset($_SESSION['user']))
	{
		echo "<script>mostrarUsuario('".$_SESSION['user']."');</script>";
	}
?>
<div class="canvasHolder">
	<canvas id="canvas" width="1280" height="720"></canvas>
</div>
<div id="tabla">
	<form action="" method="post" class="tab">
		<label>USER NAME</label><br/><br/>
		<input type="text" name="user"/><br/><br/>
		<label>PASSWORD</label><br/><br/>
		<input type="text" name="pass"/><br/><br/>
		
		<div class="btn">
			<input type="submit" name="submit" value="LOGIN"/>
		</div>
	</form>
</div>

<div id="highscore">
	<div class="spnormal">
		<center><h2>NORMAL MODE</h2></center>
		<table>
			<tr>
				<td>TOP</td>
				<td>USER</td>
				<td>SCORE</td>
				<td>ATTEMPTS</td>
			</tr>
			<?php
				$top = 1;
				$sql = "SELECT u.user, s.score, s.intentos FROM users u, spnormal s WHERE s.id_user=u.id ORDER BY s.score DESC LIMIT 10";
				$result = $conn->query($sql);
				if ($result->num_rows > 0) {
					while($row = $result->fetch_array())
					{
						echo "<tr>";
							echo "<td>".$top."</td>";
							echo "<td>".$row[0]."</td>";
							echo "<td>".$row[1]."</td>";
							echo "<td>".$row[2]."</td>";
						echo "</tr>";
						$top += 1;
					}
				}
			?>
		</table>
	</div>
	<div class="sphardcore">
		<center><h2>HARDCORE</h2></center>
		<table>
			<tr>
				<td>TOP</td>
				<td>USER</td>
				<td>SCORE</td>
				<td>ATTEMPTS</td>
			</tr>
			<?php
				$top = 1;
				$sql = "SELECT u.user, s.score, s.intentos FROM users u, sphardcore s WHERE s.id_user=u.id ORDER BY s.score DESC LIMIT 10";
				$result = $conn->query($sql);
				if ($result->num_rows > 0) {
					while($row = $result->fetch_array())
					{
						echo "<tr>";
							echo "<td>".$top."</td>";
							echo "<td>".$row[0]."</td>";
							echo "<td>".$row[1]."</td>";
							echo "<td>".$row[2]."</td>";
						echo "</tr>";
						$top += 1;
					}
				}
			?>
		</table>
	</div>
</div>
</body>
</html>