<?php

enum TransactionType {
    case INCOME;
    case EXPENSE;
}


class Transaction {
    public string $id;
    public string $user;
    public TransactionType $transactionType;
    public float $amount;
    public string $date;

    public function __construct(string $user, TransactionType $transactionType, float $amount, string $date) {
        $this->id = md5(uniqid('', true) . microtime(true));
        $this->user = $user;
        $this->transactionType = $transactionType;
        $this->amount = $amount;
        $this->date = $date;
    }
}


function calculateUserBalance(array $transactions, string $user, string $startDate, string $endDate): ? float
{
    $result = null;
    foreach ($transactions as $transaction) {
        if ($transaction->user === $user &&
            $transaction->date >= $startDate &&
            $transaction->date <= $endDate
        ) {
            $result = $transaction->transactionType === TransactionType::INCOME
                ? $result + $transaction->amount
                : $result - $transaction->amount
            ;
        }
    }
    return $result;
}

$userBalance = calculateUserBalance([
        new Transaction('user1', TransactionType::INCOME, 100, '2023-01-01'),
        new Transaction('user1', TransactionType::EXPENSE, 50, '2023-01-02'),
        new Transaction('user1', TransactionType::INCOME, 1000, '2023-01-10'),
        new Transaction('user1', TransactionType::EXPENSE, 1000, '2023-02-01'),
        new Transaction('user2', TransactionType::INCOME, 200, '2023-01-03'),
        new Transaction('user2', TransactionType::EXPENSE, 75, '2023-01-04')
    ],
    'user1',
    '2023-01-01',
    '2023-01-31'
);

echo (null === $userBalance) ? 'Немає записів, що задовільняють умовам' : "Баланс користувача: $userBalance";