/**
 * JavaScript kod dinamičnog dijela stranice: komunikacija s poslužiteljem i
 * ispis rezultata transkripcije.
 * @author Davor Penzar <davor.penzar@gmail.com>
 * @version 1.0
 * @package glagol
 */

/**
 * Izbriši ispisanu grešku.
 *
 * Objekt s id-em #redak-greska postavlja se na nevidljivi i prazni se sadržaj
 * elementa s id-em #celija-greska.
 */
let unset_error = function ()
{
  /* Provjeri poziv funkcije. */
  if (arguments.length)
    throw new Error('Bad function call.');

  /* Sakrij #redak-greska i isprazni #celija-greska. */
  $('#redak-greska').hide();
  $('#celija-greska').empty();
}

/**
 * Ispiši grešku.
 *
 * Objekt s id-em #redak-greska postavlja se na vidljivi, a sadržaj elementa s
 * id-em #celija-greska postavlja se na vrijednost argumenta err ("string").
 * @param string err poruka greške
 */
let set_error = function (err)
{
  /* Provjeri poziv funkcije. */
  if (arguments.length != 1)
    throw new Error('Bad function call.');

  /* Provjeri tip argumenta. */
  if (!(typeof err === 'string' || err instanceof String))
    throw new Error('Invalid argument type');

  /* Ispiši poruku na #celija-greska i prikaži #redak-greska. */
  $('#celija-greska').text(err);
  $('#redak-greska').show();
}

/**
 * Izvrši transkripciju.
 *
 * Argumenti dir i text šalju se metodom GET skripti get_transcription.php, a
 * odgovor se ispisuje na objekt `destination' pozivom
 *     $(destination).text(...);
 * gdje je kao argument prikazan elipsom proslijeđen odgovor.  Ako je pri
 * komunikaciji s poslužiteljem došlo do greške, odgovor se ne ispisuje, nego se
 * pozivom funkcije set_error ispisuje poruka greške.
 * @param string dir smjer transkripcije ('l2g' ili 'g2l')
 * @param string text tekst transkripcije
 * @param mixed destination ispis transkripcije pozivom $(destination).text(...)
 */
let transcribe = function (dir, text, destination)
{
  /* Provjeri poziv funkcije. */
  if (arguments.length != 3)
    throw new Error('Bad function call.');

  /* Pošalji AJAX zahtjev. */
  $.ajax(
    {
      url: 'get_transcription.php',
      type: 'GET',
      data: {'dir' : dir, 'text' : JSON.stringify(text)},
      dataType: 'json',
      error:
        function (xhr, status)
        {
          /* Ako je poruka greške prazna, ispiši 'Error.'. */
          if (status === null)
            set_error('Erro.');

          /* Ispiši poruku greške. */
          set_error(status);
        },
      success:
        function (ans)
        {
          /* Provjeri je li objekt odgovora definiran. */
          if (ans === null)
          {
            set_error('Unexpected communication error.');

            return;
          }

          /* Provjeri je li odgovor instanca klase `object'. */
          if (typeof ans !== 'object')
          {
            set_error('Unexpected communication error.');

            return;
          }

          /* Provjeri ključeve odgovora. */
          switch (Object.keys(ans).length)
          {
            case 1:
              /* Ako odgovor ima samo jedan ključ, on mora biti
               * 'transcription.' */
              if (!('transcription' in ans))
              {
                set_error('Unexpected communication error.');

                return;
              }

              break;
            case 2:
              /* Ako odgovor ima dva ključa, oni moraju biti 'transcription' i
               * 'error'. */
              if (!('transcription' in ans && 'error' in ans))
              {
                set_error('Unexpected communication error (missing keys).');

                return;
              }

              break;

            default:
              /* Nula ili strogo više od dva ključa nisu valjani odgovor. */
              set_error('Unexpected communication error.');

              return;
          }

          /* Provjeri je li odgovor rezultirao greškom. */
          if ('error' in ans)
          {
            /* Provjeri je li sadržaj greske "string" i je li transkripcija
             * nedefinirana. */
            if (
              typeof ans['error'] !== 'string' || ans['transcription'] !== null
            )
            {
              set_error('Unexpected communication error.');

              return;
            }

            set_error(ans['error']);

            return;
          }

          /* Provjeri je li sadržaj transkripcije "string". */
          if (typeof ans['transcription'] !== 'string')
          {
            set_error('Unexpected communication error.');

            return;
          }

          /* Ispiši sadržaj transkripcije. */
          $(destination).val(ans['transcription']);
        }
    }
  );
}

/* Pripremi okolinu. */
$(document).ready(
  function ()
  {
    /* Izbriši grešku. */
    unset_error();

    /* Izbriši unos u formi #transkripcija. */
    $('input[type=radio][name=dir]').prop('checked', false);
    $('input[type=radio][name=dir]').val('');
    $('#transkribiraj').val('');
    $('textarea[name=tekst]').val('');
    $('#transkripcija').off('submit');

    /* Postavi vrijednosti oznaka smjera transkripcije. */
    $('#l2g').val('l2g');
    $('#g2l').val('g2l');

    /* Postavi vrijednost gumba #transkribiraj. */
    $('#transkribiraj').val('Transkribiraj!');

    /* Ispuni područja za unos inicijalnim vrijednostima (naziv pisma na
     * odgovarajućem pismu). */
    $('#latinica').val('Latinica');
    $('#glagoljica').val("Ⰳⰾⰰⰳⱁⰾⰼⰻⱌⰰ");

    /* Definiraj ponašanje forme #transkripcija. */
    $('#transkripcija').on(
      'submit',
      function ()
      {
        /* Izbriši grešku. */
        unset_error();

        /* Provjeri je li smjer transkripcije odabran. */
        if (!$('input[type=radio][name=dir]:checked').length)
        {
          set_error('Smjer transkripcije nije odabran.');

          /* Vrati laž da se stranica ne osvježi. */
          return false;
        }

        /* Dohvati smjer transkripcije. */
        var dir = $('input[type=radio][name=dir]:checked').val();

        /* Ovisno o smjeru transkripcije, pozovi funckiju transcribe s
         * odgovarajućim argumentima. */
        switch (dir)
        {
          case 'l2g':
            /* Za smjer s latinice na glagoljicu tekst se čita iz #latinica, a
             * njegova transkripcija se ispisuje na #glagloljica. */
            transcribe('l2g', $('#latinica').val(), $('#glagoljica'));

            break;
          case 'g2l':
            /* Za smjer s latinice na glagoljicu tekst se čita iz #glagoljcia, a
             * njegova transkripcija se ispisuje na #latinica. */
            transcribe('g2l', $('#glagoljica').val(), $('#latinica'));

            break;
          default:
            /* Za neprepoznati smjer šalje se prazni tekst i nedefinirana
             * lokacija ispisa transkripcije. */
            transcribe(dir, '', null);
        }

        /* Vrati laž da se stranica ne osvjezi. */
        return false;
      }
    );
  }
);
