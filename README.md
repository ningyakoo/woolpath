# Woolpath

Código del juego WoolPath basado principalmente en HTML5 canvas y CreateJS.

# Objetivo

El objetivo del juego es llegar lo más lejos posible recolectando puntos sin salirte del camino de lana y evitando a toda costa los obstaculos que te vas encontrando a lo largo del camino. También tendrás que usar las esferas verdes teletransportate a la siguiente sin recibir daños.

La pecurialidad es que cada intento es diferente al anterior, puesto que está programado para que todo se genere de manera random.

# Modos de juego

Actualmente el juego cuenta con con dos únicos modos en el apartado singleplayer del menú: "Normal Mode" y "Hardcore".
  - Normal Mode: Este es el modo principal y más completo del juego. A medida que vayas avanzando en el escenario, te encontrarás con caminos de lanas de diferente color. Esto significa que has pasado al siguiente nivel y se verá incrementado un poco la dificultad. Este aumento de dificultad se refleja en la velocidad de traslado del escenario, la cantidad de obstaculos, la estrechez del camino, etc. Si colisionas con alguno de los bordes del camino de lana tu vida bajará una pequeña cantidad. Si colisiones con algunos de los obstaculos (spikedballs) tu vida bajará a mayor cantidad.

  - Hardcore: Este es el modo infinito donde pondrás a prueba tu reflejo y habilidad para evitar chocarte con los bordes del camino de lana que son más estrechos de lo normal. El mínimo roce te hará perder la partida. Por suerte en este modo no hay obstaculos.

# Controles

Se utiliza solo dos botones para jugar:
  - Botón izquierdo del ratón: Subes al personaje. Tendrás que dejarlo presionado para subirlo hasta donde veas conveniente. Sueltado para que baje automáticamente. 
  - Intro/Enter: Teletransporta al personaje hacia la siguiente esfera. Presiona esta tecla cuando veas que el personaje esta encima de la esfera para pasar a la siguiente y seguir el camino.

# Score

La puntuación obtenida en la partida se reflejará en el panel de GAME OVER cuando te hayas quedado sin energía. 
Esta puntuación se almacenará en la Base de datos MySQL siempre y cuando el usuario se haya registrado y haya iniciado sesión antes de comenzar la partida.

# High Scores

Podrás ver el top 10 global en el apartado High Scores del menú.

# Compartir la experiencia por Redes Sociales

En el panel de GAME OVER podrás compartir por Facebook y Twitter la puntuación que has sacado, tu mejor puntuación y el Rango obtenido en la partida.
Nota: Aún no se puede compartir por Twitter, está en desarrollo.
