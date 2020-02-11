/**
 * JavaScript kod dinamičnog dijela stranice: komunikacija s poslužiteljem i
 * ispis rezultata transkripcije.
 *
 * @author Davor Penzar <davor.penzar@gmail.com>
 * @version 1.0
 * @package glagol
 *
 */

/**
 * Izbriši ispisanu grešku.
 *
 * Objekt s id-em `'#redak-greska'` postavlja se na nevidljivi i prazni se
 * sadržaj elementa s id-em `'#celija-greska'`.
 *
 */
let unset_error = function ()
{
	// Provjeri poziv funkcije.
	if (arguments.length)
		throw new Error('Bad function call.');

	// Sakrij `'#redak-greska'` i isprazni '`#celija-greska`'.
	$('#redak-greska').hide();
	$('#celija-greska').empty();
}

/**
 * Ispiši grešku.
 *
 * Objekt s id-em `'#redak-greska'` postavlja se na vidljivi, a sadržaj elementa
 * s id-em `'#celija-greska'` postavlja se na vrijednost argumenta `err`
 * ("string").
 *
 * @param string err poruka greške
 *
 */
let set_error = function (err)
{
	// Provjeri poziv funkcije.
	if (arguments.length != 1)
		throw new Error('Bad function call.');

	// Provjeri tip argumenta.
	if (!(typeof err === 'string' || err instanceof String))
		throw new Error('Parameter `err` must be a string.');

	// Ispiši poruku na `'#celija-greska'` i prikaži `'#redak-greska'`.
	$('#celija-greska').text(err);
	$('#redak-greska').show();
}

/**
 * Izvrši transkripciju.
 *
 * Argumenti `dir` i `text` šalju se metodom `GET` skripti
 * `'get_transcription.php'`, a odgovor se ispisuje na objekt `destination`
 * pozivom
 *	 $(destination).text(...);
 * gdje je kao argument prikazan elipsom proslijeđen odgovor.  Ako je pri
 * komunikaciji s poslužiteljem došlo do greške, odgovor se ne ispisuje, nego se
 * pozivom funkcije `set_error` ispisuje poruka greške.  Za vrijeme slanja i
 * čekanja odgovora, neovisno o tomu je li uspješan ili ne, na objektu `waiting`
 * ispisuje se elipsa (`"…"`).
 *
 * @param string text tekst transkripcije
 * @param string dir smjer transkripcije (`'l2g'` ili `'g2l'`)
 * @param mixed destination ispis transkripcije pozivom `$(destination).text(...)`
 * @param mixed waiting ispis `'…'` za vrijeme čekanja transkripcije pozivom `$(waiting).text(...)`
 *
 */
let transcribe = function (text, dir, destination, waiting)
{
	// Provjeri poziv funkcije.
	if (arguments.length != 4)
		throw new Error('Bad function call.');

	// Provjeri tipove argumenata.
	if (!(typeof dir === 'string' || dir instanceof String))
		throw new Error('Parameter `dir` must be a string.');
	if (!(typeof text === 'string' || text instanceof String))
		throw new Error('Parameter `text` must be a string.');

	// Spremi staru vrijednost objekta `waiting`.
	var old_val = $(waiting).val();

	// Upiši `"…"` na objekt `waiting`.
	$(waiting).val("…");

	// Pošalji AJAX zahtjev.
	$.ajax(
		{
			'url' : 'get_transcription.php',

			'type' : 'GET',

			'data' : {'text' : JSON.stringify(text), 'dir' : dir},

			'dataType' : 'json',

			'error' :
				function (xhr, status)
				{
					// Ako je poruka greške prazna, ispiši
					// `"Greška u komunikaciji."`.
					if (status === null)
						set_error("Greška u komunikaciji.");

					// Ispiši poruku greške.
					set_error(status);
				},

			'success' :
				function (ans)
				{
					// Provjeri je li objekt odgovora definiran.
					if (ans === null)
					{
						// Ispiši grešku.
						set_error("Greška: nedefinirani odgovor poslužitelja.");

						// Prekini rad funkcije.
						return;
					}

					// Provjeri je li odgovor instanca klase `object`.
					if (typeof ans !== 'object')
					{
						// Ispiši grešku.
						set_error(
							"Greška: neočekivani tip odgovora poslužitelja."
						);

						// Prekini rad funkcije.
						return;
					}

					// Provjeri ključeve odgovora.
					switch (Object.keys(ans).length)
					{
						case 1:
							// Ako odgovor ima samo jedan ključ, on mora biti
							// `'transcription'`.
							if (!('transcription' in ans))
							{
								// Ispiši grešku.
								set_error(
									"Greška: neočekivani sadržaj odgovora " +
									"poslužitelja (1 ključ koji nije " +
									"`'transcription'`)."
								);

								// Prekini rad funkcije.
								return;
							}

							// Prekini `switch`-naredbu.
							break;

						case 2:
							// Ako odgovor ima dva ključa, oni moraju biti
							// `'transcription'` i `'error'`.
							if (!('transcription' in ans && 'error' in ans))
							{
								// Ispiši grešku.
								set_error(
									"Greška: neočekivani sadržaj odgovora " +
									"poslužitelja (2 ključa koji nisu " +
									"`'transcription'` i `'error'`)."
								);

								// Prekini rad funkcije.
								return;
							}

							// Prekini `switch`-naredbu.
							break;

						default:
							// Nula ili strogo više od dva ključa nisu valjani
							// odgovor, stoga ispiši grešku.
							set_error(
								"Greška: neočekivani sadržaj odgovora " +
								"poslužitelja (0 ili 3+ ključ((ev)a))."
							);

							// Prekini rad funkcije.
							return;
					}

					// Provjeri je li odgovor rezultirao greškom.
					if ('error' in ans)
					{
						// Provjeri je li sadržaj greške "string".
						if (
							!(
								ans['error'] === null ||
								typeof ans['error'] === 'string' ||
								ans['error'] instanceof String
							)
						)
						{
							// Ispiši grešku.
							set_error(
								"Greška: neočekivana greška na strani "
								"poslužitelja (na ključu `'error'` objekt je "+
								"krivog tipa)."
							);

							// Prekini rad funkcije.
							return;
						}

						// Provjeri je li transkripcija definirana.
						if (ans['transcription'] !== null)
						{
							// Ispiši grešku.
							set_error(
								"Greška: neočekivana greška na strani " +
								"poslužitelja (uz ključ `'error'` na ključu " +
								"`'transcription'` nije `null`)."
							);

							// Prekini rad funkcije.
							return;
						}

						// Ispiši poruku greške.
						set_error(
							ans['error'] === null ?
								"Greška: neočekivana greška na strani " +
									"poslužitelja (`null`)." :
								ans['error']
						);

						// Prekini rad funkcije.
						return;
					}

					// Provjeri je li sadržaj transkripcije "string".
					if (
						!(
							typeof ans['transcription'] === 'string' ||
							ans['transcription'] instanceof String
						)
					)
					{
						// Ispiši grešku.
						set_error("Greška: neočekivani tip transkripcije.");

						// Prekini rad funkcije.
						return;
					}

					// Ispiši sadržaj transkripcije.
					$(destination).val(ans['transcription']);
				},

			'complete' :
				function ()
				{
					// Vrati staru vrijednost na objekt `waiting`.
					$(waiting).val(old_val);
				}
		}
	);
}

// Pripremi okolinu.
$(document).ready(
	function ()
	{
		// Izbriši grešku.
		unset_error();

		// Izbriši unos u formi `'#transkripcija'`.
		$('input[type=radio][name=dir]').prop('checked', false);
		$('input[type=radio][name=dir]').val('');
		$('#transkribiraj').val('');
		$('textarea[name=tekst]').val('');
		$('#transkripcija').off('submit');

		// Namjesti veličine područja za unos.
		$('#latinica').prop('rows', 6);
		$('#latinica').prop('cols', 80);
		$('#glagoljica').prop('rows', 6);
		$('#glagoljica').prop('cols', 80);

		// Postavi vrijednosti oznaka smjera transkripcije.
		$('#l2g').val('l2g');
		$('#g2l').val('g2l');

		// Postavi vrijednost gumba `'#transkribiraj'`.
		$('#transkribiraj').val('Transkribiraj!');

		// Ispuni područja za unos inicijalnim vrijednostima (naziv pisma na
		// odgovarajućem pismu).
		$('#latinica').val('Latinica');
		$('#glagoljica').val("Ⰳⰾⰰⰳⱁⰾⰼⰻⱌⰰ");

		// Definiraj ponašanje forme `'#transkripcija'`.
		$('#transkripcija').on(
			'submit',
			function ()
			{
				// Izbriši grešku.
				unset_error();

				// Provjeri je li smjer transkripcije odabran.
				if (!$('input[type=radio][name=dir]:checked').length)
				{
					// Ispiši grešku.
					set_error('Smjer transkripcije nije odabran.');

					// Vrati laž da se stranica ne osvježi.
					return false;
				}

				// Dohvati smjer transkripcije.
				var dir = $('input[type=radio][name=dir]:checked').val();

				// Provjeri je li smjer transkripcije zadan "stringom".
				if (!(typeof dir === 'string' || dir instanceof String))
					throw new Error('Invalid type of the value dir.');

				// Ovisno o smjeru transkripcije, pozovi funckiju `transcribe` s
				// odgovarajućim argumentima.
				switch (dir)
				{
					case 'l2g':
						// Za smjer s latinice na glagoljicu tekst se čita iz
						// `'#latinica'`, a njegova se transkripcija ispisuje na
						// `'#glagloljica'`.
						transcribe(
							$('#latinica').val(),
							'l2g',
							$('#glagoljica'),
							$('#transkribiraj')
						);

						// Prekini `switch`-naredbu.
						break;

					case 'g2l':
						// Za smjer s latinice na glagoljicu tekst se čita iz
						// `'#glagoljcia'`, a njegova se transkripcija ispisuje
						// na `'#latinica'`.
						transcribe(
							$('#glagoljica').val(),
							'g2l',
							$('#latinica'),
							$('#transkribiraj')
						);

						// Prekini `switch`-naredbu.
						break;

					default:
						// Za neprepoznati smjer šalje se prazni tekst i
						// nedefinirana lokacija ispisa transkripcije.
						transcribe(dir, '', null);

						// Prekini `switch`-naredbu.
						break;
				}

				// Vrati laž da se stranica ne osvježi.
				return false;
			}
		);
	}
);
