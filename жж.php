<?php

// Создаем массив колоды карт
$deck = array(
    "2", "3", "4", "5", "6", "7", "8", "9", "10", "J", "Q", "K", "A"
);

// Создаем массив мастей
$suits = array(
    "Hearts", "Diamonds", "Clubs", "Spades"
);

// Функция для перемешивания колоды карт
function shuffleDeck(&$deck) {
    for ($i = count($deck) - 1; $i > 0; $i--) {
        $j = rand(0, $i);
        $tmp = $deck[$i];
        $deck[$i] = $deck[$j];
        $deck[$j] = $tmp;
    }
}

// Функция для получения случайной карты из колоды
function getCard(&$deck) {
    return array_shift($deck);
}

// Функция для подсчета очков на руках
function getHandValue($hand) {
    $value = 0;
    $aces = 0;
    foreach ($hand as $card) {
        if ($card == "A") {
            $aces++;
        } elseif (in_array($card, array("K", "Q", "J"))) {
            $value += 10;
        } else {
            $value += intval($card);
        }
    }
    while ($aces > 0) {
        if ($value + 11 <= 21) {
            $value += 11;
        } else {
            $value += 1;
        }
        $aces--;
    }
    return $value;
}

// Создаем колоду карт и перемешиваем ее
$deck = array_merge($deck, $deck, $deck, $deck);
shuffleDeck($deck);

// Раздаем карты игроку и дилеру
$playerHand = array(getCard($deck), getCard($deck));
$dealerHand = array(getCard($deck), getCard($deck));

// Проверяем, не набрал ли игрок 21 очко
if (getHandValue($playerHand) == 21) {
    echo "Blackjack! You win!";
    exit;
}

// Цикл игры
while (true) {
    // Показываем карты игрока и дилера
    echo "Your hand: " . implode(", ", $playerHand) . " (".getHandValue($playerHand)." points)\n";
    echo "Dealer's hand: " . implode(", ", $dealerHand) . " (".getHandValue($dealerHand)." points)\n";

    // Проверяем, не набрал ли игрок более 21 очка
    if (getHandValue($playerHand) > 21) {
        echo "Bust! You lose!";
        exit;
    }

    // Спрашиваем игрока, хочет ли он взять еще карту
    $answer = readline("Do you want to hit or stand? ");

    // Если игрок выбрал "stand", то дилер начинает брать карты
    if ($answer == "stand") {
        while (getHandValue($dealerHand) < 17) {
            $dealerHand[] = getCard($deck);
        }

        // Показываем карты игрока и дилера
        echo "Your hand: " . implode(", ", $playerHand) . " (".getHandValue($playerHand)." points)\n";
        echo "Dealer's hand: " . implode(", ", $dealerHand) . " (".getHandValue($dealerHand)." points)\n";

        // Проверяем, не набрал ли дилер более 21 очка
        if (getHandValue($dealerHand) > 21) {
            echo "Dealer bust! You win!";
            exit;
        }

        // Сравниваем очки игрока и дилера
        if (getHandValue($playerHand) > getHandValue($dealerHand)) {
            echo "You win!";
        } elseif (getHandValue($playerHand) < getHandValue($dealerHand)) {
            echo "You lose!";
        } else {
            echo "Push!";
        }
        exit;
    }

    // Если игрок выбрал "hit", то он берет еще одну карту
    $playerHand[] = getCard($deck);
}
