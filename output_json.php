<?php
/**
 * Definicija funkcije za ispis u JSON formatu i prekid izvršavanja skripte.
 *
 * @author Davor Penzar <davor.penzar@gmail.com>
 * @version 1.0
 * @package glagol
 *
 */

/**
 * Ispiši poruku u JSON formatu i po potrebi zavrsi izvrsavanje skripte.
 *
 * Postavlja se odgovarajuće HTTP zaglavlje ("header") i naredbom `echo`
 * ispisuje se dana poruka u JSON formatu.  Ako izlazno stanje nije `NULL`,
 * izvrsavanje skripte prekida se pozivom funkcije `exit` s danim izlaznim
 * stanjem; u suprotnom nije osiguran ispis (ne poziva se `flush()`) pri
 * završetku funkcije.
 *
 * @param mixed $message poruka čija se JSON reprezentacija ispisuje
 * @param null|int $exit_status (optional) izlazno stanje
 *
 */
function output_json ($message, $exit_status = 0)
{
	// Ako je previše argumenata dano, izbaci iznimku.
	if (func_num_args() > 2)
		throw new Exception('Too many arguments given.');

	// Ako `$exit_status` nije odgovarajućeg tipa, izbaci iznimku.
	if (!(is_null($exit_status) || is_int($exit_status)))
		throw new Exception('Exit status must be `NULL` or an integer.');

	// Postavi odgovarajuće HTTP zaglavlje ("header").
	header('Content-Type: Application/json; charset=utf-8');

	// Ispiši JSON reprezentaciju dane poruke.
	echo json_encode($message) . "\n";

	// Ako izlazno stanje nije `NULL`, osiguraj ispis i završi izvršavanje
	// skripte.
	if (!is_null($exit_status))
	{
		// Osiguraj ispis.
		flush();

		// Završi izvršavanje skripte.
		exit($exit_status);
	}
}
