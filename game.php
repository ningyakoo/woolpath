	<?php 
		session_start();
		require "header.php";
		include "connection.php";
		
		
	?>
	
	<style>
		body
		{
			background: url(images/fondo1.jpg) no-repeat fixed center;
			-webkit-background-size: cover;
			-moz-background-size: cover;
			-o-background-size: cover;
			background-size: cover;		
			margin:0;
			overflow-x: hidden;
			overflow-y: hidden;
		}
	
		@media screen and (min-width: 1280px) and (min-height: 720px)
		{
			.canvasHolder
			{
				position: absolute;
				top: 50%; 
				left: 50%; 
				width: 1280px; 
				height: 720px; 
				margin-top: -360px; 
				margin-left: -620px; 
			}
		}
	
		#canvas
		{
			background-color: #FFF;
			border: 2px dotted #000;
			border-radius: 15px;
		}
		
		#fb{
			display: none;
			position: relative;
			margin-left: 50%;
			margin-top: 50%;
			width: 100px;
			height: 100px;
			
		}
	</style>
		
	<script>
		var resX;
		collisionMethod = ndgmr.checkPixelCollision;
		var m1, m2;
		var collisionSeg;
		var formatoM;
		var start, gameover, txInicial, textoInicial;
		var canvas, stage, screenWidth, screenHeight, widthMax, heightMax;
		var shapeRastro, size, color, oldX, oldY, inverso, nuevoSeg, idColor;
		var loader, sky;
		var player, playerB, marcoVida, vida
		var teclado = [];
		var tocado;
		var puntos, t1, tPuntos, t2, t3, tEffect;
		var objetosMove = [];
		var data = [], lvl, contGlobal, cambiaLvl;
		var move, tX, tY, tiempoD, activar, tGenerarLineas;
		var velGame, distanciaX;
		var prinX, prinxY, finX, finY;
		var enemigos = [], tenemigo01;
		var estrellas = [], puntosSalto = [];
		var pulsando;
		var cambiarSentido, cambiarControl;
		var directionInv, controlInv;
		var tBif, bif;
		var tFlotante = [];
		var damageObjects = [];
		var datos;
		var polvoEstellar, marcoPE, cantidadPE, poderEstelar, activarPolvos, tInmortal, inmortal;
		var modo;
		var animacionGO1, animacionGO2, tAnimGO2, dirAnimGO2;
		var flash, tFlash;
		var shapeMainn;
		var bestScore, user;
		
		manifest = [
			{src: "sky.png", id: "sky"},
			{src: "player.png", id: "player"},
			{src: "playerD.png", id: "player2"},
			{src: "star.png", id: "star"},
			{src: "apple.png", id: "apple"},
			{src: "spikedBall.png", id: "spikeB"},
			{src: "esfera.png", id: "puntoS"},
			{src: "F1.jpg", id: "F1"},
			{src: "F2.png", id: "F2"},
			{src: "F3.png", id: "F3"},
			{src: "F4.png", id: "F4"},
			{src: "F5.png", id: "F5"},
			{src: "F6.png", id: "F6"},
			{src: "F7.png", id: "F7"},
			{src: "F8.png", id: "F8"},
			{src: "F9.png", id: "F9"},
			{src: "facebook.png", id: "facebook"},
			{src: "twitter.png", id: "twitter"},
			{src: "play.png", id: "play"},
			{src: "Rgold.png", id: "Rgold"},
			{src: "Rplate.png", id: "Rplate"},
			{src: "Rbronze.png", id: "Rbronze"},
			{src: "exit.png", id: "exit"},
		];
	
		function init(a, b)
		{
			examples.showDistractor();	
			formatoM = false;
			canvas = document.getElementById("canvas");
			stage = new createjs.Stage(canvas);
			if(window.innerWidth < 1280 || window.innerHeight < 720)
			{
				stage.canvas.width = window.innerWidth;
				stage.canvas.height = window.innerHeight;
				formatoM = true;
			}
							
			screenWidth = stage.canvas.width;
			screenHeight = stage.canvas.height; 
			resX = getResolution();
			
			
			m1 = a;
			m2 = b;
			
			resX = getResolution();
			
			cargarModo();		
		
			loader = new createjs.LoadQueue(false);
			loader.addEventListener("complete", handleComplete);
			loader.loadManifest(manifest, true, "images/");
			//loader.loadManifest(data, true);
		}
		
		function generarBotones()
		{
			var shape = new createjs.Shape();		
			shape.graphics.beginLinearGradientFill(["#FFF","#9C9C9C","#FFF"], [0, 1, .2], 0, screenHeight-100*resX, 0, screenHeight-5*resX).beginStroke("#000").setStrokeStyle(1).drawRoundRect(Math.abs(stage.x)+160*resX, screenHeight-100*resX, 120*resX, 60*resX, 10*resX, 10*resX, 10*resX, 10*resX).endFill();			
			shape.regX = 60*resX;
			shape.regY = 30*resX;
			stage.addChild(shape);
			objetosMove.push(shape);
			
			shape.addEventListener("mouseover", function()
			{
				$("body").css("cursor", "pointer");
				
			});

			shape.addEventListener("mouseout", function()
			{
				$("body").css("cursor", "default");
				
			});

			shape.addEventListener("click", function()
			{
				teclado[13] = true;
			});
			
			var sph = new createjs.Bitmap(loader.getResult("puntoS"));
			sph.regX = loader.getResult("puntoS").width/2;
			sph.regY = loader.getResult("puntoS").height/2;
			sph.x = Math.abs(stage.x)+160*resX;
			sph.y = screenHeight-100*resX
			sph.scaleX = sph.scaleY = 1*resX;
			stage.addChild(sph);
			objetosMove.push(sph);
		}
		
		function cargarModo()
		{
			switch(m1)
			{
				case 1:
					switch(m2)
					{
						case 1:
							data = [
								{cantidadL: 40, size: 160, color: '#83571D', distanciaX: 200, puntoS: [], lineas: [], collisionObj: [], newSeg: 10, spikedBall: 0, velSpikedBall: 2, probMoveSpikedBall: 0, star: 65, velGame: 3.3, velGameIn: 7, velGameSlow: 1.2, velGameTemp: 3.3, tEnemigos: 800, maxAlto: 190, velYPlayerUp: 1, velYPlayerDown: 1, maxY: 8, sumVelUp: 0.33, sumVelDown: 0.28, shape: new createjs.Shape(), arrayShape: [], probMoveSeg: 0, puntosL: 0.1},
								{cantidadL: 40, size: 155, color: '#1D5F83', distanciaX: 200, puntoS: [], lineas: [], collisionObj: [], newSeg: 15, spikedBall: 30, velSpikedBall: 2, probMoveSpikedBall: 0, star: 65, velGame: 3.6, velGameIn: 7.3, velGameSlow: 1.2, velGameTemp: 3.6, tEnemigos: 700, maxAlto: 180, velYPlayerUp: 1, velYPlayerDown: 1, maxY: 8.5, sumVelUp: 0.36, sumVelDown: 0.3, shape: new createjs.Shape(), arrayShape: [], probMoveSeg: 0, puntosL: 0.2},
								{cantidadL: 40, size: 155, color: '#1D835D', distanciaX: 200, puntoS: [], lineas: [], collisionObj: [], newSeg: 20, spikedBall: 45, velSpikedBall: 2, probMoveSpikedBall: 55, star: 65, velGame: 4, velGameIn: 7.7, velGameSlow: 1.2, velGameTemp: 4, tEnemigos: 700, maxAlto: 165, velYPlayerUp: 1, velYPlayerDown: 1, maxY: 9, sumVelUp: 0.39, sumVelDown: 0.32, shape: new createjs.Shape(), arrayShape: [], probMoveSeg: 0, puntosL: 0.2},
								{cantidadL: 40, size: 150, color: '#1D835D', distanciaX: 200, puntoS: [], lineas: [], collisionObj: [], newSeg: 20, spikedBall: 60, velSpikedBall: 2.5, probMoveSpikedBall: 60, star: 65, velGame: 4.3, velGameIn: 7.7, velGameSlow: 1.2, velGameTemp: 4.3, tEnemigos: 650, maxAlto: 160, velYPlayerUp: 1, velYPlayerDown: 1, maxY: 9.4, sumVelUp: 0.41, sumVelDown: 0.34, shape: new createjs.Shape(), arrayShape: [], probMoveSeg: 0, puntosL: 0.25},
							]
							collisionSeg = (data[0].size/4+12);
							modo = 'spnormal';
							break;
						case 2:
							data = [
								{cantidadL: 500, size: 85*resX+10, color: '#83571D', distanciaX: 250*resX, puntoS: [], lineas: [], collisionObj: [], newSeg: 5, spikedBall: 0, velSpikedBall: 0, probMoveSpikedBall: 0, star: 0, velGame: 4.2*resX, velGameTemp: 3.3, tEnemigos: 0, maxAlto: 160, velYPlayerUp: 1*resX, velYPlayerDown: 1*resX, maxY: 9*resX, sumVelUp: 0.36*resX, sumVelDown: 0.3*resX, shape: new createjs.Shape(), arrayShape: [], probMoveSeg: 0, puntosL: 0.015},
							]
							collisionSeg = (data[0].size/4-10*resX);
							modo = 'sphardcore';
							break;
					}
					break;
			}
			
			datos = data[0];
		}
		
		function handleComplete()
		{
			examples.hideDistractor();
			
			lvl = 0;
			gameover = false;
			polvoEstellar = 0;
			poderEstelar = false;
			tInmortal = 400;
			inmortal = false;
			activarPolvos = false;
			animacionGO1 = false;
			animacionGO2 = false;
			dirAnimGO2 = false;
			tAnimGO2 = 18;
			
			sky = new createjs.Shape();
			sky.graphics.beginBitmapFill(loader.getResult("sky")).drawRect(0, 0, screenWidth, screenHeight);
			sky.x = 0;
			sky.y = 0;
			stage.addChild(sky);
			
			oldX = 250;
			oldY = (screenHeight/2-50);
			tX = (oldX+300);
			tY = (screenHeight/2-50);
			
			
	
			for(var i = 0; i < data.length; i++)
			{
				
				for(var y = 0; y < data[i].cantidadL; y++)
				{
					var sh = new createjs.Shape();
					stage.addChild(sh);
					
					//shapeMain.cache(0, 0, tX+100, screenHeight);
					
					sh.graphics.beginBitmapStroke(loader.getResult("F"+(i+1)))
								  .setStrokeStyle(data[i].size, "round")
								  .moveTo(oldX, oldY)
								  .lineTo(tX, tY);
					
					stage.update();				
					//shapeMain.updateCache("source-overlay");
					//shape.updateCache();
					//shapeMain.graphics.clear();

					
					var d = {prX: oldX, prY: oldY, fnX: tX, fnY: tY, move: false, dir: ''};
					data[i].lineas.push(d);
					
					if(Math.ceil(Math.random()*100) <= data[i].newSeg || y == data[i].cantidadL-1)
					{
						var imgPunto = loader.getResult("puntoS");
						
						var p = new createjs.Bitmap(imgPunto);
						p.regX = imgPunto.width/2;
						p.regY = imgPunto.height/2;
						p.x = tX;
						/*if(Math.ceil(Math.random()*2) == 1)
							p.y = tY+50;
						else
							p.y = tY-50;*/
						p.y = tY			
						p.scaleX = p.scaleY = (data[i].size/70);
						p.rotation = 0;
						p.inverso = false;
						p.next = true;
						stage.addChild(p);
						data[i].puntoS.push(p);
										
						oldX = tX+data[i].distanciaX*2;
						oldY = tY;
						tX = oldX+data[i].distanciaX;
						tY = Math.random()*((screenHeight-50)-data[i].maxAlto)+data[i].maxAlto;

						var p = new createjs.Bitmap(imgPunto);
						p.regX = imgPunto.width/2;
						p.regY = imgPunto.height/2;
						p.x = oldX;
						p.y = oldY;
						p.scaleX = p.scaleY = 1.2;
						p.rotation = 0;	
						p.inverso = true;	
						p.next = false;
						//stage.addChild(p);
						data[i].puntoS.push(p);
					}
					else
					{	
						if(Math.ceil(Math.random()*100) <= data[i].probMoveSeg){
							data[i].lineas[data[i].lineas.length-1].move = true;
							data[i].lineas[data[i].lineas.length-1].dir = 'down';
						}

						oldX = tX;
						oldY = tY;
						tX += data[i].distanciaX;
						tY = Math.random()*((screenHeight-50)-data[i].maxAlto)+data[i].maxAlto;

						if(Math.ceil(Math.random()*100) <= data[i].star)
						{
							var imgStar = loader.getResult("star");
							var star = new createjs.Bitmap(imgStar);
							star.x = tX-imgStar.width/2;
							star.y = tY + Math.ceil(Math.random()*(50-(-50))+(-50))
							//star.y = tY-imgStar.height/2;					
							star.width = imgStar.width;
							star.height = imgStar.height;
							star.name = 'star';
							//stage.addChild(star);
							data[i].collisionObj.push(star);
								
						}
					
						if(Math.ceil(Math.random()*100) <= data[i].spikedBall)
						{
							var imgSpike = loader.getResult("spikeB");
							var spike = new createjs.Bitmap(imgSpike);
							spike.regX = imgSpike.width/2;
							spike.regY = imgSpike.height/2;													
							spike.x = oldX;
							spike.y = oldY + Math.ceil(Math.random()*(50-(-50))+(-50))
							spike.limiteSup = oldY - 60;
							spike.limiteInf = oldY + 60;
							spike.scaleX = spike.scaleY = 1;
							spike.rotation = 0;	
							spike.name = 'spike';
							if(Math.ceil(Math.random()*100) <= data[i].probMoveSpikedBall){
								spike.move = true;
								spike.dir = 'd';
							}else{
								spike.move = false;
							}
							//stage.addChild(spike);
							data[i].collisionObj.push(spike);
						}
					}

					data[i].arrayShape.push(sh);
				}
				
			}
		
			generarPlayer();
			
			for(var i in data)
			{
				var dato = data[i];
				for(var y in dato.collisionObj)
				{
					var obj = dato.collisionObj[y];
					stage.addChild(obj);
				}
				for(var y in dato.puntoS)
				{
					var obj = dato.puntoS[y];
					stage.addChild(obj);
				}
			}
			
			if(formatoM){
				generarBotones();
			}
		
			stage.addChild(playerB);
					
			agregarEventosTeclado();
					
			start = false;
			action = false;	
					
			puntos = 0;
			inverso = false;
			pulsando = false;
			
			cambiarSentido = 150;
			cambiarControl = 150;
			directionInv = false;
			controlInv = false;
						
			tenemigo01 = 0;
			activar = false;
			
			bif = false;
			tBifurcacion = 0
			cambiaLvl = 0;
			tExtraEnergy = null;
			tDesaparicion = 120;
			
			txInicial = new createjs.Text("PRESS MOUSE TO START", "60px Luckiest Guy", "#FFF");
			txInicial.outline = 7;
			txInicial.x = screenWidth/2-315;
			txInicial.y = screenHeight/2-50;
			
			textoInicial = txInicial.clone();
			textoInicial.outline = false;
			textoInicial.x = screenWidth/2-315;
			textoInicial.y = screenHeight/2-50;
			textoInicial.color = "#000";
			stage.addChild(txInicial, textoInicial);
			
			
			t1 = new createjs.Text("Score: "+puntos, "21px Luckiest Guy", "#000");
			t1.outline = 5;
			t1.x = 40;
			t1.y = 20;
			objetosMove.push(t1);
			
			tPuntos = t1.clone();
			tPuntos.outline = false;
			tPuntos.x = 40;
			tPuntos.y = 20;
			tPuntos.color = "#EFBC3C";
			stage.addChild(t1, tPuntos);
			objetosMove.push(tPuntos);
			t3 = new createjs.Text(Math.floor(cambiarSentido/60), "40px Luckiest Guy", "#000");
			t3.outline = 2;
			t3.x = (screenWidth/2)-30;
			t3.y = 20;
			objetosMove.push(t3);
			
			tEffect = t3.clone();
			tEffect.outline = false;
			tEffect.x = (screenWidth/2)-30;
			tEffect.y = 20;
			tEffect.color = "red";						
			objetosMove.push(tEffect);
			
			
			//stage.addChild(t3, tEffect);
			marcoVida = new createjs.Shape();
			marcoVida.graphics.setStrokeStyle(1).beginStroke("#000").drawRect(0,0,player.energy,30).endFill();
			marcoVida.x = (screenWidth/2)-75;
			marcoVida.y = screenHeight-60;
			objetosMove.push(marcoVida);
			
			vida = new createjs.Shape();
			vida.graphics.beginFill("#74EB2A").drawRect(0,0,player.energy,30).endFill();
			vida.x = (screenWidth/2)-75;
			vida.y = screenHeight-60;
			objetosMove.push(vida);
			
			marcoPE = new createjs.Shape();
			marcoPE.graphics.setStrokeStyle(1).beginStroke("#000").drawRect(0,0,60,-200).endFill();
			marcoPE.x = screenWidth-80;
			marcoPE.y = 220;
			objetosMove.push(marcoPE);
			
			cantidadPE = new createjs.Shape();
			cantidadPE.graphics.beginFill("#FFE72E").drawRect(0,0,60,0).endFill();
			cantidadPE.x = screenWidth-80;
			cantidadPE.y = 220
			objetosMove.push(cantidadPE);
			
			if(modo != 'sphardcore')
				stage.addChild(vida, marcoVida, cantidadPE, marcoPE);		
										
			createjs.Touch.enable(stage);
			stage.enableMouseOver(60);
			
			createjs.Ticker.setFPS(60);
			//stage.addEventListener("stagemousemove", moveMouse);
			stage.addEventListener("stagemousedown", downMouse);
			stage.addEventListener("stagemouseup", upMouse);			
			createjs.Ticker.addEventListener("tick", tick);
		}
		
		function generarPlayer()
		{	
			player = new createjs.Shape();
			player.graphics.beginFill("blue").drawCircle(0,0,18*resX).endFill();
			player.x = 250;
			player.y = (screenHeight/2-50);
			player.velocidad = 6*resX;
			player.vX = 3*resX;
			player.radio = 18*resX;
			player.energy = 100;
			//stage.addChild(player);
			
			var imgplayer = loader.getResult("player");
			playerB = new createjs.Bitmap(imgplayer);		
			playerB.x = player.x;
			playerB.y = player.y;
			playerB.scaleX = playerB.scaleY = 1*resX
			playerB.regX = imgplayer.width/2
			playerB.regY = imgplayer.height/2
	
		}
		
		function moveMouse(evt)
		{
			
		}
		
		function downMouse(evt)
		{	
			if(!gameover){
				if(txInicial != null && textoInicial != null){
					stage.removeChild(txInicial, textoInicial);
					//stage.removeChild(textoInicial);
					txInicial = null;
					textoInicial = null;
				}
				
				
				start = true;
				
				pulsando = true;
				datos.velYPlayerDown = 1*resX;
			}
		
			/*var tempX = evt.stageX;
			var tempY = evt.stageY;
			
			if(stage.x < 0){
				evt.stageX -= stage.x;
			}
			if(stage.y < 0){
				evt.stageY -= stage.y;
			}				

			evt.stageX = tempX;
			evt.stageY = tempY;	*/	
			
		}
		
		function upMouse(evt)
		{
			pulsando = false;
			datos.velYPlayerUp = 1*resX;		
		}
		
		function eventosTeclado()
		{
			if(start){
				if(modo == 'spnoob'){
					/** Tecla S - Abajo **/
					if(teclado[83])
					{
						player.y += datos.velGame+2;
						playerB.y += datos.velGame+2;	
						//teclado[83] = false;
					}
					
					/** Tecla W - Arriba **/
					if(teclado[87])
					{
						player.y -= datos.velGame+2;
						playerB.y -= datos.velGame+2;	
						//teclado[87] = false;
					}
				}
			
				if(!teclado[13])
				{
					if(activarPolvos){
						datos.velGame = datos.velGameTemp;
						activarPolvos = false;
						playerB.image = loader.getResult("player");
						
					}

				}
			
				if(teclado[13])
				{
					var salto = false;
					for(var i = 0; i < datos.puntoS.length; i++)
					{
						var punto = datos.puntoS[i];
						
						var intersection = collisionMethod(playerB,punto,0);
						
						if(intersection && punto.next)
						{
							salto = true;
						
							var tempX = stage.x;
							if(punto.inverso){
								player.x = datos.puntoS[i-1].x;
								player.y = datos.puntoS[i-1].y;
							}
							else{
								player.x = datos.puntoS[i+1].x;
								player.y = datos.puntoS[i+1].y;
							}
							
							playerB.x = player.x;
							playerB.y = player.y;
							stage.x = -player.x + 350*resX;
							for(var i in objetosMove)
							{
								var o = objetosMove[i];
								o.x += tempX-stage.x;
							}
							
							break;
						}
					}
					
					teclado[13] = false;
					
					if(!salto)
					{
						teclado[13] = true;
						datos.velYPlayerDown = 1;
					}
					/*if(!salto && !inmortal)
					{
						if(poderEstelar)
						{
							inmortal = true;
							polvoEstellar = 0;
							teclado[32] = false;
							datos.velGame = datos.velGameIn;
							stage.addChild(tEffect, t3);
						}
						else
						{
							if(!activarPolvos && polvoEstellar > 0){
								datos.velGame = datos.velGameSlow;
								activarPolvos = true;
								//playerB.image = loader.getResult("player3");
							}
							if(activarPolvos)
								polvoEstellar -= 0.1;
							teclado[32] = true;
						}
						
					}*/
					
					
				}
			}
			
			if(teclado[80])
			{
				start =! start;
				teclado[80] = false;
			}
			
			
			
		}
		
		function agregarEventosTeclado()
		{
			agregarEvento(document, "keydown", function(e){
				teclado[e.keyCode] = true;		
				//alert(e.keyCode);
			});	
			agregarEvento(document, "keyup", function(e){
				teclado[e.keyCode] = false;			
			});
			function agregarEvento(elemento, evento, funcion)
			{
				if(elemento.addEventListener)
				{
					elemento.addEventListener(evento, funcion, false);	
				}
				else if(elemento.attachEvent)
				{
					elemento.attachEvent(evento, funcion);	
				}
			}
		}
		
		
		
		function tick(e)
		{	
			eventosTeclado();
			
			if(animacionGO1){
				stage.alpha -= 0.12;
				if(stage.alpha <= 0){
					stage.alpha = 1;
					animacionGO1 = false;
				}
			}
			
			if(animacionGO2)
			{			
				if(dirAnimGO2){
					playerB.y += tAnimGO2;
					tAnimGO2 += 1;
					if(playerB.y >= screenHeight+100){
						animacionGO2 = false;
						panelGameOver();
					}
				}
				else{
					playerB.y -= tAnimGO2;
					tAnimGO2 -= 1;
				}
				
				if(tAnimGO2 <= 0 && !dirAnimGO2)
					dirAnimGO2 = true;
			}
			
			if(start){
			
				//shapeMain.x -= datos.velGame;
				player.x += datos.velGame;
				playerB.x += datos.velGame;	
				stage.x -= datos.velGame;
				sky.x = Math.abs(stage.x);
			
				for(var i = 0; i < datos.arrayShape.length; i++)
				{
					var segmento = datos.arrayShape[i];
					if(segmento.move)
					{
						var prx = datos.lineas[i].prX;
						var pry = datos.lineas[i].prY;
						var fnx = datos.lineas[i].fnX;
						var fny = datos.lineas[i].fnY;
						
						if(segmento.dir == 'down'){
							//pry += 3;
							fny += 5;
						}else if(segmento.dir == 'up'){
							//pry -= 3;
							fny -= 5;
						}
						
						tY = Math.random()*((screenHeight-50)-datos.maxAlto)+datos.maxAlto;
						segmento.graphics.clear().beginBitmapStroke(loader.getResult("T0"+(lvl+1)))
								  .setStrokeStyle(datos.size, "round")
								  .moveTo(prx, pry)
								  .lineTo(fnx, fny);
								  
						datos.lineas[i].prY = pry;		  
						datos.lineas[i].fnY = fny;
						
								  
						if(fny >= screenHeight-50)
							segmento.dir = 'up'
						else if(fny <= datos.maxAlto)
							segmento.dir = 'down'
							
					}
				}
							
				t1.text = "Score: "+Math.floor(puntos);
				tPuntos.text = "Score: "+Math.floor(puntos);
				
				if(player.energy > 50)
					vida.graphics.clear().beginFill("#74EB2A").drawRect(0,0,player.energy,30).endFill();
				else if(player.energy <= 50 && player.energy > 25)
					vida.graphics.clear().beginFill("yellow").drawRect(0,0,player.energy,30).endFill();
				else if(player.energy <= 25)
					vida.graphics.clear().beginFill("red").drawRect(0,0,player.energy,30).endFill();
			
				if(player.energy <= 0)
				{
					animacionGO2 = true;
					animacionGO1 = true;
					start = false;
					operacionesBD();
					gameover = true;			
					var circleHit = new createjs.Shape();
					circleHit.graphics.beginFill("red").drawCircle(playerB.x, playerB.y, 9).endFill();
					circleHit.alpha = 0.6;
					stage.addChild(circleHit);
				}
				
				if(polvoEstellar > 200){
					polvoEstellar = 0
					puntos += 1500;
					var tx = new createjs.Text("+1500 pt.", "20px Luckiest Guy", "#000");
					tx.outline = 4;
					tx.x = marcoPE.x-30;
					tx.y = 50;
					tx.desaparicion = 120;
					
					var t = tx.clone();
					t.outline = false;
					t.x = marcoPE.x-30;
					t.y = 50;
					t.color = "#F3ED38";
					t.id = objetosMove.length;
					t.desaparicion = 120;
					stage.addChild(tx,t);
					tFlotante.push(t);
					tFlotante.push(tx);
					
					player.energy += 20;
					if(player.energy > 100)
						player.energy = 100;
					
					var tx = new createjs.Text("+20", "20px Luckiest Guy", "#000");
					tx.outline = 4;
					tx.x = vida.x+45;
					tx.y = vida.y-30;
					tx.desaparicion = 120;
					tx.id = objetosMove.length;
					tFlotante.push(tx);
					
					var t = tx.clone();
					t.outline = false;
					t.x = vida.x+45;
					t.y = vida.y-30;
					t.id = objetosMove.length;
					t.desaparicion = 120;
					t.color = "#9DFF2D";
					tFlotante.push(t);
					stage.addChild(tx, t);		
					
				}
			
				if(polvoEstellar == 200)
				{
					cantidadPE.graphics.clear().beginFill("#FCFF00").drawRect(0,0,60,-(polvoEstellar)).endFill();
					poderEstelar = true;
				}
				else
				{
					cantidadPE.graphics.clear().beginFill("#FFE72E").drawRect(0,0,60,-(polvoEstellar)).endFill();
					poderEstelar = false;
				}
				
				if(activarPolvos)
				{
					if(polvoEstellar > 0){
						polvoEstellar -= 1;
					}
					else{
						datos.velGame = datos.velGame*2;
						activarPolvos = false;
					}
						
				}					

				
				checkCollision();
				
				for(var t in tFlotante)
				{
					var texto = tFlotante[t];
					
					if(texto.desaparicion <= 0)
					{
						if(texto.alpha > 0){
							//stage.removeChild(texto);
							texto.alpha -= 0.1;
							//stage.addChild(texto);
						}else{
							objetosMove.splice(texto.id, 1);
							tFlotante.splice(t,1);							
						}
					}
					
					texto.desaparicion -= 1;
				}
					
				if(modo != 'spnoob'){
					if(controlInv)
					{
						if(pulsando){
							if(datos.velYPlayerUp <= datos.maxY)
							{
								datos.velYPlayerUp += datos.sumVelUp;
							}
							player.y += datos.velYPlayerUp;
							playerB.y += datos.velYPlayerUp;
						}else{
							if(datos.velYPlayerDown <= datos.maxY)
							{
								datos.velYPlayerDown += datos.sumVelDown;
							}
							player.y -= datos.velYPlayerDown;
							playerB.y -= datos.velYPlayerDown;
						}
					}
					else
					{
						if(pulsando){
							if(datos.velYPlayerUp <= datos.maxY)
							{
								datos.velYPlayerUp += datos.sumVelUp;
							}
							player.y -= datos.velYPlayerUp;
							playerB.y -= datos.velYPlayerUp;
						}else{
							if(datos.velYPlayerDown <= datos.maxY)
							{
								datos.velYPlayerDown += datos.sumVelDown
							}
							player.y += datos.velYPlayerDown;
							playerB.y += datos.velYPlayerDown;
						}
					}	
				}

				movimientosEnemigos();
				
				for(var s in datos.puntoS)
				{
					var punto = datos.puntoS[s];
					punto.rotation += 20;
				}
				
				for(var o in datos.collisionObj)
				{
					var damage = datos.collisionObj[o];
					if(damage.name == 'spike'){
						damage.rotation += 10;
						if(damage.move){
							if(damage.dir == 'd')
								damage.y += datos.velSpikedBall
							else if(damage.dir == 'u')
								damage.y -= datos.velSpikedBall
								
							if(damage.y <= damage.limiteSup)
								damage.dir = 'd'
							else if(damage.y >= damage.limiteInf)
								damage.dir = 'u'
						}
					}
				}
				
				if(tenemigo01 >= datos.tEnemigos && datos.tEnemigos > 0)
				{
					generarEnemigo();
					tenemigo01 = 0;
				}
				
				if(cambiarSentido < 0){
					directionInv = false;					
					datos.velGame = -datos.velGame;
					cambiarSentido = 180;
					stage.removeChild(t3);
					stage.removeChild(tEffect);
				}
				
				if(directionInv){
					cambiarSentido--;			
					t3.text = Math.ceil(cambiarSentido/60);
					tEffect.text = Math.ceil(cambiarSentido/60);
				}
			
				for(var i in objetosMove)
				{
					var o = objetosMove[i];
					o.x += datos.velGame;
				}
				
				if(cambiarControl < 0){
					controlInv = false;
					cambiarControl = 180;
					stage.removeChild(t3);
					stage.removeChild(tEffect);
	
				}
								
				if(controlInv){
					cambiarControl--;
					t3.text = Math.ceil(cambiarControl/60);
					tEffect.text = Math.ceil(cambiarControl/60);
				}		

				if(inmortal){
					tInmortal--;
					t3.text = Math.ceil(tInmortal/60);
					tEffect.text = Math.ceil(tInmortal/60);
					puntos += 2;
				}
				else
				{
					puntos += datos.puntosL;
				}
					
				if(tInmortal <= 0)
				{
					
					tInmortal = 420;
					inmortal = false;
					datos.velGame = datos.velGameTemp;
					stage.removeChild(t3);
					stage.removeChild(tEffect);
				}
				
				if(player.x >= datos.puntoS[datos.puntoS.length-1].x)
				{
					nextLevel();
				}
			
					
				tenemigo01++;
				tBifurcacion++;
				tFlash -= 1;
			}
			
			stage.update();
		}
		
		function nextLevel()
		{		
			for(var i in datos.arrayShape)
			{
				var d = datos.arrayShape[i];
				d.graphics.clear();
				stage.removeChild(d);
			}
			
			for(var i in datos.collisionObj)
			{
				var o = datos.collisionObj[i];
				stage.removeChild(o);
			}
			
			data.shift();
			datos = data[0];		
			lvl += 1;
			collisionSeg = datos.size/4+12;				
			player.energy += 50;
			if(player.energy > 100)
				player.energy = 100;
			var tExtraEnergy = new createjs.Text("+50", "20px Luckiest Guy", "#000");
			tExtraEnergy.alpha = 1;
			tExtraEnergy.x = vida.x+45;
			tExtraEnergy.y = vida.y - 30;
			tExtraEnergy.id = objetosMove.length;
			tExtraEnergy.desaparicion = 120;
			stage.addChild(tExtraEnergy);
			objetosMove.push(tExtraEnergy);
			tFlotante.push(tExtraEnergy);
		}
		
		function checkCollision()
		{
			var colision = false;
			for(var i = 0; i < datos.lineas.length; i++){
				var prinX = datos.lineas[i].prX;
				var prinY = datos.lineas[i].prY;
				var finX = datos.lineas[i].fnX;
				var finY = datos.lineas[i].fnY;
			
				if(distancia_punto_linea(prinX, prinY, finX, finY, player) <= (player.radio+collisionSeg))
				{	
					colision = true;
					break;
				}

			}
			
			if(inmortal)
			{
				//playerB.image = loader.getResult("playerIn");
			}
			else
			{
				if(colision){
				
					playerB.image = loader.getResult("player");
				}else{
					if(modo == 'sphardcore')
					{
						player.energy = 0;
					}
					else
					{
						player.energy -= 2;	
					}
					playerB.image = loader.getResult("player2");
											
				}
			}
			
			for(var i = 0; i < datos.collisionObj.length; i++)
			{
				var obj = datos.collisionObj[i];
				
				var intersection = collisionMethod(playerB,obj,0);
				
				if(intersection)
				{
					if(obj.name=='star')
						starEffect(i, obj);
					if(obj.name=='spike')
						spikedBallEffect(i, obj);
			
				}			
			}

			/*for(var i = 0; i < damageObjects.length; i++)
			{
				var damage = damageObjects[i];
				var intersection = collisionMethod(playerB,damage,0);
				
				if(intersection)
				{
					
				}
			}*/
		}
		
		function starEffect(i, obj)
		{
			var tx = new createjs.Text("+50 pt.", "20px Luckiest Guy", "#000");
			tx.outline = 4;
			tx.x = obj.x;
			tx.y = obj.y-30;
			tx.desaparicion = 140;
			
			var t = tx.clone();
			t.outline = false;
			t.x = obj.x;
			t.y = obj.y-30;
			t.color = "#F3ED38";
			t.id = objetosMove.length;
			t.desaparicion = 140;
			stage.addChild(tx,t);
			tFlotante.push(t);
			tFlotante.push(tx);
		
			puntos += 50;
			polvoEstellar += 10;
			stage.removeChild(obj);
			datos.collisionObj.splice(i, 1);
		}
		
		function spikedBallEffect(i)
		{
			if(!inmortal)
				player.energy -= 5;
			playerB.image = loader.getResult("player2");
		}
		
		function colisionRectangulo(obj)
		{
			var circleDistanceX = Math.abs(player.x - obj.x - obj.width/2);
			var circleDistanceY = Math.abs(player.y - obj.y - obj.height/2);
			 
			if (circleDistanceX > (obj.width/2 + player.radio)) { return false; }
			if (circleDistanceY > (obj.height/2 + player.radio)) { return false; }
			 
			if (circleDistanceX <= (obj.width/2)) { return true; }
			if (circleDistanceY <= (obj.height/2)) { return true; }
			 
			var cornerDistance_sq = Math.pow(circleDistanceX - obj.width/2, 2) + Math.pow(circleDistanceY - obj.height/2, 2);
			 
			return (cornerDistance_sq <= (Math.pow(player.radio, 2)));

		}
		
		function magnitud(pX, pY)
		{
		  return Math.sqrt( pX*pX + pY*pY );
		}
		
		function distancia_punto_linea(prinX, prinY, finX, finY, punto)
		{
			var dirX = finX - prinX;
			var dirY = finY - prinY;
			  
			var den = dirX*dirX + dirY*dirY;  
			//var den = Math.sqrt( dirX*dirX + dirY*dirY );
			if ( den == 0 )
			{
				dirX = punto.x-prinX;
				dirY = punto.y-prinY;
				return magnitud(dirX, dirY);
			}
			  
			var a = (punto.x - prinX) * dirX;
			var b = (punto.y - prinY) * dirY;
			var u = (a+b)/den;
			
			 if ( u < 0 ) 
				u = 0;
			 else if ( u > 1 )
				u = 1;
			  
			var x = prinX + u*dirX;
			var y = prinY + u*dirY;
			  
			dirX = punto.x - x;
			dirY = punto.y - y;
			return magnitud(dirX, dirY);
		}
		
		function movimientosEnemigos()
		{
			for(var i in enemigos)
			{
				var enemigo = enemigos[i];
				//var nextX = enemigo.x;
				//var nextY = enemigo.y;
				enemigo.x -= enemigo.velX[enemigo.num]
				enemigo.y -= enemigo.velY[enemigo.num]
				
				if(enemigo.dir == 'r')
				{
					if(enemigo.x >= enemigo.finX[enemigo.num])
					{
						//alert('ENTRA1');
						enemigo.num += 1;
					}
					if(enemigo.num >= 15)
					{
						stage.removeChild(enemigo);
						enemigos.splice(i, 1);
						break;
					}
				}
				else if(enemigo.dir == 'l')
				{
					if(enemigo.x <= enemigo.finX[enemigo.num])
					{
						//alert('ENTRA2');
						enemigo.num += 1;
					}
					if(enemigo.num >= datos.lineas.length-1)
					{
						stage.removeChild(enemigo);
						enemigos.splice(i, 1);
						break;
					}
				}
				
				var intersection = collisionMethod(playerB,enemigo,0);
				
				if(intersection)
				{
					if(!inmortal){
						switch(enemigo.tipo)
						{
							case 0:
								datos.velGame = -datos.velGame;
								directionInv = true;							
								t3.text = Math.floor(cambiarSentido/60);
								tEffect.text = Math.ceil(cambiarSentido/60);
			
								break;
							case 1:
								if(directionInv){
									cambiarControl += 180
								}
								else{
									controlInv = true;							
									t3.text = Math.floor(cambiarControl/60);
									tEffect.text = Math.ceil(cambiarControl/60);
								}
								break;
							case 2:
								break;
						}
						
						stage.addChild(tEffect,t3);	
					}
			
					stage.removeChild(enemigo);
					enemigos.splice(i, 1);
				}	
			}
		}
		
		function generarEnemigo()
		{
			var rand = datos.lineas.length-1//Math.ceil(Math.random()*(datos.lineas.length-6)+6);
			//alert(datos.lineas[rand].prX)
			//var rand2 = Math.floor(Math.random()*2);
			var rand2 = 1
			
			var imgEfecto2 = loader.getResult("apple")
			var enemigo01;
			//var enemigo01 = new createjs.Shape();
			enemigo01 = new createjs.Bitmap(imgEfecto2);
			/*switch(rand2)
			{
				case 0:
					//color = 'green';
					//enemigo01
					break;
				case 1:
					//color = 'yellow';
					enemigo01 = new createjs.Bitmap(imgEfecto2);
					break;
				case 2:
					//color = 'white';
					break;
					
			}	*/
			enemigo01.x = datos.lineas[rand].prX;	
			/*if(Math.floor(Math.random()*2) == 0)
				enemigo01.y = datos.lineas[rand].prY + 40;
			else
				enemigo01.y = datos.lineas[rand].prY - 40;*/
			enemigo01.y = datos.lineas[rand].prY + Math.ceil(Math.random()*(45+45)-45);
			enemigo01.num = 0;
			enemigo01.velX = [];
			enemigo01.velY = [];
			enemigo01.finX = [];
			enemigo01.finY = [];
			enemigo01.regX = imgEfecto2.width/2
			enemigo01.regY = imgEfecto2.height/2
			enemigo01.dir = '';
			enemigo01.tipo = rand2;
			/*if(enemigo01.x < player.x)
			{
				enemigo01.dir = 'r';
				for(var i = 0; i < 15; i++)
				{
					var b = datos.lineas[rand+i].prX-datos.lineas[rand+i].fnX;
					var	c = datos.lineas[rand+i].prY-datos.lineas[rand+i].fnY;
					var a = Math.sqrt(b*b+c*c)

					var vT = 0;

					var vT = ((datos.velYPlayer+4)/a);
					var vX = (vT*b);
					var vY = (vT*c);
					
					enemigo01.velX.push(vX);
					enemigo01.velY.push(vY);
					enemigo01.finX.push(datos.lineas[rand+i].fnX);
					enemigo01.finY.push(datos.lineas[rand+i].fnY);
				}
			}
			else*/ 
			if(enemigo01.x > player.x)
			{
				enemigo01.dir = 'l';
				for(var i = 0; i < datos.lineas.length-1; i++)
				{
					var b = datos.lineas[rand-i].prX-datos.lineas[rand-(i+1)].prX;
					var	c = datos.lineas[rand-i].prY-datos.lineas[rand-(i+1)].prY;
					var a = Math.sqrt(b*b+c*c)

					var vT = 0;

					var vT = ((datos.velGame*2)/a);
					var vX = (vT*b);
					var vY = (vT*c);
					
					enemigo01.velX.push(vX);
					enemigo01.velY.push(vY);
					enemigo01.finX.push(datos.lineas[rand-(i+1)].prX);
					enemigo01.finY.push(datos.lineas[rand-(i+1)].prY);
				}
			}
			
			stage.addChild(enemigo01);		
			enemigos.push(enemigo01)
		}
		
		function panelGameOver()
		{				
			$("#fb").css("display", "block");
			
			t2 = new createjs.Text("GAME OVER", 80*resX+"px Luckiest Guy", "#000");
			t2.outline = 4;
			t2.x = Math.abs(stage.x)+(screenWidth/2)-215*resX;
			t2.y = (screenHeight/2-250*resX);
			
			tGameOver = t2.clone();
			tGameOver.outline = false;
			tGameOver.color = "#FA5563";
			stage.addChild(t2, tGameOver);
					
			/** CONTAINER **/

			var container = createTile();
			
			var bordeTx1 = new createjs.Text("RANGE", 20*resX+"px Luckiest Guy", "#843434");
			bordeTx1.outline = 3;
			bordeTx1.x = 30*resX;
			bordeTx1.y = 20*resX;		
			text1 = bordeTx1.clone();
			text1.outline = false;
			text1.color = "#E49956"
			
			var imgRange = null;
			
			if(modo == 'sphardcore'){			
				if(puntos >= 15 && puntos < 40)
					imgRange = new createjs.Bitmap(loader.getResult("Rbronze"));
				else if(puntos >= 40 && puntos < 70)
					imgRange = new createjs.Bitmap(loader.getResult("Rplate"));
				else if(puntos >= 70)
					imgRange = new createjs.Bitmap(loader.getResult("Rgold"));	
			}
			else if(modo == 'spnormal'){
				if(puntos >= 5000 && puntos < 10000)
					imgRange = new createjs.Bitmap(loader.getResult("Rbronze"));
				else if(puntos >= 10000 && puntos < 18000)
					imgRange = new createjs.Bitmap(loader.getResult("Rplate"));
				else if(puntos >= 18000)
					imgRange = new createjs.Bitmap(loader.getResult("Rgold"));	
			}
			
			if(imgRange != null)
			{
				imgRange.x = 30*resX;
				imgRange.y = 58*resX;
				imgRange.scaleX = imgRange.scaleY = 0.5*resX;
				container.addChild(imgRange);
			}
			else{
				imgRange = new createjs.Shape();
				imgRange.graphics.beginFill("#D2CFA6").drawCircle(60*resX, 95*resX, 26*resX).endFill();
				container.addChild(imgRange);
			}
			
			/** SCORE TEXT **/
			var bordeTx2 = new createjs.Text("SCORE", 20*resX+"px Luckiest Guy", "#843434");
			bordeTx2.outline = 3;
			bordeTx2.x = 170*resX;
			bordeTx2.y = 20*resX;		
			text2 = bordeTx2.clone();
			text2.outline = false;
			text2.color = "#E49956";
			/*****************/
			
			/** SCORE **/
			var scoreTx = new createjs.Text(Math.floor(puntos), 36*resX+"px Luckiest Guy", "#000");
			scoreTx.outline = 4;
			scoreTx.x = 165*resX;
			scoreTx.y = 65*resX;		
			scoreText = scoreTx.clone();
			scoreText.outline = false;
			scoreText.color = "#FFF";
			/*****************/
			
			/** BEST TEXT **/
			var bordeTx3 = new createjs.Text("BEST", 20*resX+"px Luckiest Guy", "#843434");
			bordeTx3.outline = 3;
			bordeTx3.x = 310*resX;
			bordeTx3.y = 20*resX;		
			text3 = bordeTx3.clone();
			text3.outline = false;
			text3.color = "#E49956"
			/****************/
			
			/** BEST **/
			var bestTx = new createjs.Text(bestScore, 36*resX+"px Luckiest Guy", "#000");
			bestTx.outline = 4;
			bestTx.x = 300*resX;
			bestTx.y = 65*resX;		
			bestText = bestTx.clone();
			bestText.outline = false;
			bestText.color = "#FFF";
			/*****************/
			
			container.addChild(bordeTx1, text1, bordeTx2, text2, bordeTx3, text3, scoreTx, scoreText, bestTx, bestText, imgRange);		
			container.x = Math.abs(stage.x)+(screenWidth/2)-210*resX;
			container.y = Math.abs(stage.y)+(screenHeight/2)-140*resX;
			stage.addChild(container);
			
			/***************************/
					
			/** PLAY **/
			var shape = new createjs.Shape();		
			shape.graphics.beginLinearGradientFill(["#FFF","#9C9C9C","#FFF"], [0, 1, .2], 0, screenHeight/2+175*resX, 0, screenHeight/2+335*resX).beginStroke("#000").setStrokeStyle(1).drawRoundRect(Math.abs(stage.x)+(screenWidth/2)-20*resX, screenHeight/2+175*resX, 160*resX, 80*resX, 10, 10, 10, 10).endFill();			
			//shape1.x = 500;
			//shape1.y = 500;
			shape.regX = 80*resX;
			shape.regY = 40*resX;
			stage.addChild(shape);
			
			shape.addEventListener("mouseover", function()
			{
				$("body").css("cursor", "pointer");
				play.scaleX = play.scaleY = 0.5*resX
			});

			shape.addEventListener("mouseout", function()
			{
				$("body").css("cursor", "default");
				play.scaleX = play.scaleY = 0.4*resX
			});

			shape.addEventListener("click", function()
			{
				cargarDatos();
			});
			
			var play = new createjs.Bitmap(loader.getResult("play"));
			play.regX = loader.getResult("play").width/2;
			play.regY = loader.getResult("play").height/2;
			play.x = Math.abs(stage.x)+(screenWidth/2)-20*resX;
			play.y = screenHeight/2+175*resX;
			play.scaleX = play.scaleY = 0.4*resX
			stage.addChild(play);
			
			/**********************/
			
			
			/** FACEBOOK **/
			var shape = new createjs.Shape();		
			shape.graphics.beginLinearGradientFill(["#FFF","#9C9C9C","#FFF"], [0, 1, .2], 0, screenHeight/2+80*resX, 0, screenHeight/2+180*resX).beginStroke("#000").setStrokeStyle(1).drawRoundRect(Math.abs(stage.x)+(screenWidth/2)-90*resX, screenHeight/2+80*resX, 100*resX, 60*resX, 10, 10, 10, 10).endFill();			
			shape.regX = 50*resX;
			shape.regY = 30*resX;
			stage.addChild(shape);
			
			shape.addEventListener("mouseover", function()
			{
				$("body").css("cursor", "pointer");
				facebook.scaleX = facebook.scaleY = 0.45*resX
			});

			shape.addEventListener("mouseout", function()
			{
				$("body").css("cursor", "default");
				facebook.scaleX = facebook.scaleY = 0.4*resX
			});

			shape.addEventListener("click", function()
			{	
				var nuevaCapt = getHexaRandom();
				
				exportAndSaveCanvas(nuevaCapt, function(person){
						$.ajax({
						url:'upload/img_'+nuevaCapt+'.png',
						type:'HEAD',
						success: function()
						{						
							window.open('http://www.facebook.com/sharer.php?u=http://www.woolpath.esy.es/upload/img_'+nuevaCapt+'.png', '_blank');

						}
						});
					
				});		
			});
			
			var facebook = new createjs.Bitmap(loader.getResult("facebook"));
			facebook.regX = loader.getResult("facebook").width/2;
			facebook.regY = loader.getResult("facebook").height/2;
			facebook.x = Math.abs(stage.x)+(screenWidth/2)-90*resX;
			facebook.y = screenHeight/2+80*resX;
			facebook.scaleX = facebook.scaleY = 0.4*resX
			stage.addChild(facebook);
			
			/*********************/
			
			/** TWITTER **/
			var shape = new createjs.Shape();		
			shape.graphics.beginLinearGradientFill(["#FFF","#9C9C9C","#FFF"], [0, 1, .2], 0, screenHeight/2+80*resX, 0, screenHeight/2+180*resX).beginStroke("#000").setStrokeStyle(1).drawRoundRect(Math.abs(stage.x)+(screenWidth/2)+45*resX, screenHeight/2+80*resX, 100*resX, 60*resX, 10, 10, 10, 10).endFill();			
			shape.regX = 50*resX;
			shape.regY = 30*resX;
			stage.addChild(shape);
			
			shape.addEventListener("mouseover", function()
			{
				$("body").css("cursor", "pointer");
				twitter.scaleX = twitter.scaleY = 0.45*resX
			});

			shape.addEventListener("mouseout", function()
			{
				$("body").css("cursor", "default");
				twitter.scaleX = twitter.scaleY = 0.4*resX
			});

			shape.addEventListener("click", function()
			{
				cargarDatos();
			});
			
			var twitter = new createjs.Bitmap(loader.getResult("twitter"));
			twitter.regX = loader.getResult("twitter").width/2;
			twitter.regY = loader.getResult("twitter").height/2;
			twitter.x = Math.abs(stage.x)+(screenWidth/2)+45*resX;
			twitter.y = screenHeight/2+80*resX;
			twitter.scaleX = twitter.scaleY = 0.4*resX
			stage.addChild(twitter);
			
			/*********************/
			
			/** EXIT **/
			var shape = new createjs.Shape();		
			shape.graphics.beginLinearGradientFill(["#FFF","#9C9C9C","#FFF"], [0, 1, .2], 0, screenHeight-70*resX, 0, screenHeight+30*resX).beginStroke("#000").setStrokeStyle(1).drawRoundRect(Math.abs(stage.x)+90*resX, screenHeight-70*resX, 100*resX, 60*resX, 10, 10, 10, 10).endFill();			
			shape.regX = 50*resX;
			shape.regY = 30*resX;
			stage.addChild(shape);
			
			shape.addEventListener("mouseover", function()
			{
				$("body").css("cursor", "pointer");
				exit.scaleX = exit.scaleY = 0.5*resX
			});

			shape.addEventListener("mouseout", function()
			{
				$("body").css("cursor", "default");
				exit.scaleX = exit.scaleY = 0.4*resX
			});

			shape.addEventListener("click", function()
			{
				location.href = "index.php";
			});
			
			var exit = new createjs.Bitmap(loader.getResult("exit"));
			exit.regX = loader.getResult("exit").width/2;
			exit.regY = loader.getResult("exit").height/2;
			exit.x = Math.abs(stage.x)+90*resX
			exit.y = screenHeight-70*resX;
			exit.scaleX = exit.scaleY = 0.4*resX
			stage.addChild(exit);
			
			/************************/
			
		
		}
		
		function createTile() {
			var container = new createjs.Container();
			var bg = new createjs.Shape();
			bg.graphics.beginFill('#F4F0C4').beginStroke("#000").setStrokeStyle(2).drawRoundRect(0*resX, 0*resX, 400*resX, 170*resX, 10, 10, 10, 10).endFill();
			
			container.addChild(bg);
			return container;
		}
		
		function cargarDatos()
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
		
		function getResolution()
		{
			var fscale = 0;
			if(screenHeight > screenWidth)
			{
				screenHeight = (screenWidth/screenHeight)*screenWidth; 
				
			}

			var x = screenWidth/1280;
			var y = screenHeight/720;
			
			if(x > y)
				fscale = y;
			else
				fscale = x;
			
			return fscale;
			
		}
		
		function operacionesBD()
		{
			objAjax =new XMLHttpRequest();
			objAjax.onreadystatechange = actualizarBD;
			objAjax.open('POST', 'updateData.php', true);
			objAjax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			objAjax.send('tipo='+modo+'&score='+Math.floor(puntos));
			
		}
		
		function actualizarBD()
		{
			if(objAjax.readyState == 4) {
			  if(objAjax.status == 200) {
				bestScore = objAjax.responseText;
				if(bestScore <= 0)
					bestScore = Math.floor(puntos);
			  }
			}
		}
		
		exportAndSaveCanvas = function (r, response) {
			// Get the canvas screenshot as PNG
			var screenshot = Canvas2Image.saveAsPNG(canvas, true);

			// This is a little trick to get the SRC attribute from the generated <img> screenshot
			canvas.parentNode.appendChild(screenshot);
			screenshot.id = "canvasimage";		
			data = $('#canvasimage').attr('src');
			canvas.parentNode.removeChild(screenshot);


			// Send the screenshot to PHP to save it on the server
			var url = 'upload/export.php';
			$.ajax({
				async : false,			 
				url: url,
				dataType: 'text',
				data: {
				base64data : data,
				rand : r
				},
				type: "POST",
				success: function(data){
					response(data);
				}
			});
		}
		
		function aleatorio(inferior,superior){ 
		   var numPosibilidades = superior - inferior; 
		   var aleat = Math.random() * numPosibilidades; 
		   aleat = Math.floor(aleat); 
		   return parseInt(inferior) + aleat; 
		}
		
		function getHexaRandom(){ 
		   var hexadecimal = new Array("0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F") 
		   var valor_aleatorio = ""; 
		   for (i=0;i<6;i++){ 
			  var posarray = aleatorio(0,hexadecimal.length) 
			  valor_aleatorio += hexadecimal[posarray] 
		   } 
		   return valor_aleatorio;
		}
		
		function urlExists(url)
		{
			var http = new XMLHttpRequest();
			http.open('HEAD', url, false);
			http.send();
			return http.status!=404;
		}
		
		function publicarFacebook(nuevaCapt)
		{
			window.open('http://www.facebook.com/sharer.php?u=http://www.woolpath.esy.es/upload/img_'+nuevaCapt+'.png', '_blank');
		}

		
	</script>
</head>
<body onload="init(<?php echo $_POST['m1'].", ".$_POST['m2'] ?>)">
<?php
	if(isset($_SESSION['user']))
	{
		echo "<script>user = '".$_SESSION['user']."'</script>";
	}
?>
<div class="canvasHolder">
	<canvas id="canvas" width="1280" height="720"></canvas>
</div>

</body>
</html>