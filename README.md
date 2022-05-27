Baza to MySQL

Endpointy

Każdy zaczyna się od /api/coffee-machine

{GET} /status - Wypisze czy maszyna jest włączona czy nie
{PUT} /status - Właczamy lub wyłaczamy maszynę
    params 
        {turn} 
    allowed values 
        turn - {"on|off"}
    required params
        {turn}

{GET} /specification - Wypisze dane o specyfikacji

{GET} /content - Wypisze dane o tym ile jest wody, kawy, mleka lub jaka jest ustawiona moc kawy.
{PUT} /content - Dolanie wody, mleka lub dosypanie kawy
    params 
        {coffeeStatus, milkStatus, waterStatus}
    allowed values
        coffeeStatus, milkStatus, waterStatus - Integer between 1 - 100

{GET} /coffee - Wypisze możliwe kawy do zrobienia
{POST} /coffee - Umożliwia zrobienie kawy
    params 
        {type}
    allowed values
        type - {"espresso|americano|coffeeWithMilk|coffeeWithFrothedMilk"}

{PUT} /coffee/power - Ustawia moc kawy
    params
        {power}
    allowed values
        power - int in 0-10 range