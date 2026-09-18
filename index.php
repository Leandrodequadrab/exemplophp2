<?php
// jogo.php — jogo local para 2 jogadores em um único arquivo.
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Duelo Local - 2 Players</title>

<style>
* {
    box-sizing: border-box;
    user-select: none;
}

body {
    margin: 0;
    min-height: 100vh;
    background: #090d18;
    color: white;
    font-family: Arial, Helvetica, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
}

#gameBox {
    width: 1000px;
    max-width: 96vw;
}

h1 {
    text-align: center;
    margin: 0 0 10px;
    letter-spacing: 4px;
    color: #f8fafc;
}

.controls {
    display: flex;
    justify-content: space-between;
    color: #94a3b8;
    font-size: 14px;
    margin-bottom: 10px;
}

.hud {
    display: grid;
    grid-template-columns: 1fr 150px 1fr;
    gap: 20px;
    align-items: center;
    margin-bottom: 12px;
}

.playerInfo {
    background: #111827;
    padding: 12px;
    border-radius: 12px;
    border: 1px solid #263244;
}

.playerInfo.right {
    text-align: right;
}

.name {
    font-weight: bold;
    margin-bottom: 7px;
}

.life {
    height: 20px;
    background: #2b3445;
    border-radius: 20px;
    overflow: hidden;
}

.lifeBar {
    width: 100%;
    height: 100%;
    background: #22c55e;
    transition: width .15s;
}

#life2 {
    margin-left: auto;
}

.score {
    margin-top: 7px;
    color: #facc15;
}

.centerHud {
    text-align: center;
}

#timer {
    font-size: 35px;
    font-weight: bold;
}

#arena {
    width: 100%;
    height: 570px;
    position: relative;
    overflow: hidden;
    border-radius: 16px;
    border: 2px solid #334155;
    background:
        radial-gradient(circle at 50% 20%, rgba(96,165,250,.14), transparent 30%),
        linear-gradient(#172033 0 72%, #283548 72% 100%);
    box-shadow: 0 20px 70px rgba(0,0,0,.5);
}

.moon {
    position: absolute;
    width: 90px;
    height: 90px;
    border-radius: 50%;
    background: #dbeafe;
    top: 45px;
    left: 455px;
    box-shadow: 0 0 50px rgba(219,234,254,.5);
}

.platform {
    position: absolute;
    background: #475569;
    border-top: 5px solid #64748b;
    border-radius: 5px;
}

.p1 {
    width: 190px;
    height: 20px;
    left: 150px;
    top: 340px;
}

.p2 {
    width: 190px;
    height: 20px;
    right: 150px;
    top: 340px;
}

.middle {
    width: 180px;
    height: 20px;
    left: calc(50% - 90px);
    top: 220px;
}

.fighter {
    width: 52px;
    height: 75px;
    position: absolute;
    border-radius: 13px 13px 7px 7px;
    transform-origin: center;
}

#fighter1 {
    background: #3b82f6;
    box-shadow: 0 0 20px rgba(59,130,246,.6);
}

#fighter2 {
    background: #ef4444;
    box-shadow: 0 0 20px rgba(239,68,68,.6);
}

.head {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #fed7aa;
    position: absolute;
    top: -22px;
    left: 10px;
}

.eye {
    width: 5px;
    height: 5px;
    background: #111827;
    border-radius: 50%;
    position: absolute;
    top: 11px;
}

.eye1 { left: 7px; }
.eye2 { right: 7px; }

.weapon {
    position: absolute;
    width: 43px;
    height: 8px;
    background: #e2e8f0;
    border-radius: 4px;
    top: 25px;
    right: -36px;
    transform-origin: left center;
    display: none;
}

.fighter.attacking .weapon {
    display: block;
    animation: sword .16s linear;
}

@keyframes sword {
    0%   { transform: rotate(-60deg); }
    50%  { transform: rotate(10deg); }
    100% { transform: rotate(55deg); }
}

.damage {
    position: absolute;
    font-size: 25px;
    font-weight: bold;
    color: #facc15;
    pointer-events: none;
    animation: damageFloat .7s forwards;
}

@keyframes damageFloat {
    from {
        transform: translateY(0);
        opacity: 1;
    }
    to {
        transform: translateY(-60px);
        opacity: 0;
    }
}

#message {
    position: absolute;
    inset: 0;
    background: rgba(2,6,23,.80);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 20;
}

.messageCard {
    width: 440px;
    text-align: center;
    padding: 35px;
    border-radius: 20px;
    background: #111827;
    border: 1px solid #475569;
}

.messageCard h2 {
    font-size: 40px;
    margin: 0 0 15px;
}

button {
    border: 0;
    background: #22c55e;
    color: #052e16;
    padding: 14px 28px;
    border-radius: 9px;
    font-size: 17px;
    font-weight: bold;
    cursor: pointer;
}

button:hover {
    filter: brightness(1.15);
}

.hit {
    animation: hitFlash .18s;
}

@keyframes hitFlash {
    50% {
        filter: brightness(3);
    }
}

.footer {
    text-align: center;
    color: #64748b;
    font-size: 12px;
    margin-top: 10px;
}
</style>
</head>

<body>

<div id="gameBox">

    <h1>⚔ DUELO LOCAL ⚔</h1>

    <div class="controls">
        <div>🔵 PLAYER 1 — A/D mover • W pular • F atacar</div>
        <div>PLAYER 2 — ←/→ mover • ↑ pular • L atacar 🔴</div>
    </div>

    <div class="hud">

        <div class="playerInfo">
            <div class="name">🔵 PLAYER 1</div>
            <div class="life">
                <div class="lifeBar" id="life1"></div>
            </div>
            <div class="score">
                Vitórias: <span id="score1">0</span>
            </div>
        </div>

        <div class="centerHud">
            <div id="timer">60</div>
            <small>TEMPO</small>
        </div>

        <div class="playerInfo right">
            <div class="name">PLAYER 2 🔴</div>
            <div class="life">
                <div class="lifeBar" id="life2"></div>
            </div>
            <div class="score">
                Vitórias: <span id="score2">0</span>
            </div>
        </div>

    </div>

    <div id="arena">

        <div class="moon"></div>

        <div class="platform p1"></div>
        <div class="platform p2"></div>
        <div class="platform middle"></div>

        <div class="fighter" id="fighter1">
            <div class="head">
                <div class="eye eye1"></div>
                <div class="eye eye2"></div>
            </div>
            <div class="weapon"></div>
        </div>

        <div class="fighter" id="fighter2">
            <div class="head">
                <div class="eye eye1"></div>
                <div class="eye eye2"></div>
            </div>
            <div class="weapon"></div>
        </div>

        <div id="message">
            <div class="messageCard">
                <h2>⚔ DUELO ⚔</h2>
                <p>Dois jogadores no mesmo teclado.</p>
                <p>
                    🔵 <b>P1:</b> A D W + F
                    <br><br>
                    🔴 <b>P2:</b> ← → ↑ + L
                </p>

                <button onclick="startGame()">
                    COMEÇAR
                </button>
            </div>
        </div>

    </div>

    <div class="footer">
        Jogo executado localmente pelo Apache/PHP
    </div>

</div>


<script>

const arena = document.getElementById("arena");
const f1El = document.getElementById("fighter1");
const f2El = document.getElementById("fighter2");

const keys = {};

let running = false;
let timer = 60;
let timerInterval = null;

let score1 = 0;
let score2 = 0;

const gravity = 0.65;
const ground = 455;

function createPlayer(x, color) {
    return {
        x: x,
        y: ground,
        vx: 0,
        vy: 0,
        width: 52,
        height: 75,

        life: 100,

        speed: 5,
        jump: 13,

        grounded: true,

        attacking: false,
        attackCooldown: false,

        direction: 1
    };
}

let p1;
let p2;


function resetPlayers() {

    p1 = createPlayer(120);
    p2 = createPlayer(820);

    p1.direction = 1;
    p2.direction = -1;

    updateLife();

}


function startGame() {

    clearInterval(timerInterval);

    resetPlayers();

    timer = 60;
    running = true;

    document.getElementById("message").style.display = "none";

    document.getElementById("timer").textContent = timer;

    timerInterval = setInterval(() => {

        if (!running) return;

        timer--;

        document.getElementById("timer").textContent = timer;

        if (timer <= 0) {

            if (p1.life > p2.life) {
                endGame(1);
            }
            else if (p2.life > p1.life) {
                endGame(2);
            }
            else {
                endGame(0);
            }

        }

    }, 1000);

}


document.addEventListener("keydown", function(e) {

    const blocked = [
        "ArrowUp",
        "ArrowDown",
        "ArrowLeft",
        "ArrowRight",
        " "
    ];

    if (blocked.includes(e.key)) {
        e.preventDefault();
    }

    keys[e.key.toLowerCase()] = true;

});


document.addEventListener("keyup", function(e) {
    keys[e.key.toLowerCase()] = false;
});


function controls() {

    if (!running) return;

    // PLAYER 1

    p1.vx = 0;

    if (keys["a"]) {
        p1.vx = -p1.speed;
        p1.direction = -1;
    }

    if (keys["d"]) {
        p1.vx = p1.speed;
        p1.direction = 1;
    }

    if (keys["w"] && p1.grounded) {
        p1.vy = -p1.jump;
        p1.grounded = false;
    }

    if (keys["f"]) {
        attack(p1, p2, f1El, 1);
    }


    // PLAYER 2

    p2.vx = 0;

    if (keys["arrowleft"]) {
        p2.vx = -p2.speed;
        p2.direction = -1;
    }

    if (keys["arrowright"]) {
        p2.vx = p2.speed;
        p2.direction = 1;
    }

    if (keys["arrowup"] && p2.grounded) {
        p2.vy = -p2.jump;
        p2.grounded = false;
    }

    if (keys["l"]) {
        attack(p2, p1, f2El, 2);
    }

}


function physics(player) {

    player.vy += gravity;

    player.x += player.vx;
    player.y += player.vy;


    // LIMITES LATERAIS

    if (player.x < 0) {
        player.x = 0;
    }

    if (player.x > arena.clientWidth - player.width) {
        player.x = arena.clientWidth - player.width;
    }


    // CHÃO

    if (player.y >= ground) {

        player.y = ground;
        player.vy = 0;
        player.grounded = true;

    }

}


function attack(attacker, enemy, element, playerNumber) {

    if (attacker.attackCooldown) {
        return;
    }

    attacker.attackCooldown = true;
    attacker.attacking = true;

    element.classList.add("attacking");

    const attackerCenter =
        attacker.x + attacker.width / 2;

    const enemyCenter =
        enemy.x + enemy.width / 2;

    const distanceX =
        Math.abs(attackerCenter - enemyCenter);

    const distanceY =
        Math.abs(attacker.y - enemy.y);


    // VERIFICA SE O INIMIGO ESTÁ NA DIREÇÃO DO ATAQUE

    let correctDirection = false;

    if (
        attacker.direction === 1 &&
        enemyCenter > attackerCenter
    ) {
        correctDirection = true;
    }

    if (
        attacker.direction === -1 &&
        enemyCenter < attackerCenter
    ) {
        correctDirection = true;
    }


    if (
        distanceX < 100 &&
        distanceY < 75 &&
        correctDirection
    ) {

        const damage =
            Math.floor(Math.random() * 7) + 8;

        enemy.life -= damage;

        if (enemy.life < 0) {
            enemy.life = 0;
        }

        // KNOCKBACK

        enemy.vx =
            attacker.direction * 12;

        enemy.vy = -5;

        showDamage(
            enemy.x,
            enemy.y,
            damage
        );

        const enemyElement =
            playerNumber === 1
            ? f2El
            : f1El;

        enemyElement.classList.add("hit");

        setTimeout(() => {
            enemyElement.classList.remove("hit");
        }, 180);

        updateLife();


        if (enemy.life <= 0) {

            if (playerNumber === 1) {
                endGame(1);
            } else {
                endGame(2);
            }

        }

    }


    setTimeout(() => {

        attacker.attacking = false;

        element.classList.remove("attacking");

    }, 180);


    setTimeout(() => {

        attacker.attackCooldown = false;

    }, 450);

}


function showDamage(x, y, damage) {

    const div =
        document.createElement("div");

    div.className = "damage";

    div.textContent =
        "-" + damage;

    div.style.left =
        x + "px";

    div.style.top =
        (y - 20) + "px";

    arena.appendChild(div);

    setTimeout(() => {
        div.remove();
    }, 700);

}


function updateLife() {

    document.getElementById("life1")
        .style.width = p1.life + "%";

    document.getElementById("life2")
        .style.width = p2.life + "%";


    changeLifeColor(
        document.getElementById("life1"),
        p1.life
    );

    changeLifeColor(
        document.getElementById("life2"),
        p2.life
    );

}


function changeLifeColor(element, life) {

    if (life > 60) {
        element.style.background = "#22c55e";
    }
    else if (life > 30) {
        element.style.background = "#eab308";
    }
    else {
        element.style.background = "#ef4444";
    }

}


function endGame(winner) {

    if (!running) return;

    running = false;

    clearInterval(timerInterval);

    let text = "";

    if (winner === 1) {

        score1++;

        document.getElementById("score1")
            .textContent = score1;

        text = "🔵 PLAYER 1 VENCEU!";

    }
    else if (winner === 2) {

        score2++;

        document.getElementById("score2")
            .textContent = score2;

        text = "🔴 PLAYER 2 VENCEU!";

    }
    else {

        text = "🤝 EMPATE!";

    }


    const message =
        document.getElementById("message");

    message.innerHTML = `
        <div class="messageCard">

            <h2>${text}</h2>

            <p>
                Placar:
                🔵 ${score1}
                ×
                ${score2} 🔴
            </p>

            <button onclick="startGame()">
                REVANCHE
            </button>

        </div>
    `;

    message.style.display = "flex";

}


function render() {

    f1El.style.left =
        p1.x + "px";

    f1El.style.top =
        p1.y + "px";

    f2El.style.left =
        p2.x + "px";

    f2El.style.top =
        p2.y + "px";


    // Vira visualmente o personagem

    f1El.style.transform =
        p1.direction === 1
        ? "scaleX(1)"
        : "scaleX(-1)";

    f2El.style.transform =
        p2.direction === 1
        ? "scaleX(1)"
        : "scaleX(-1)";

}


function gameLoop() {

    if (running) {

        controls();

        physics(p1);
        physics(p2);

        render();

    }

    requestAnimationFrame(gameLoop);

}


// Inicialização

resetPlayers();
render();
gameLoop();

</script>

</body>
</html>
