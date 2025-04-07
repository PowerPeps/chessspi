<style>
    .chessboard {
        display: grid;
        grid-template: repeat(8, 1fr) / repeat(8, 1fr);
        width: 400px;
        height: 400px;
    }

    .chessboard > div {
        background: #eee;
    }

    .chessboard > div:nth-child(odd):nth-child(n + 9):nth-child(-n + 16),
    .chessboard > div:nth-child(even):nth-child(n + 1):nth-child(-n + 8),
    .chessboard > div:nth-child(odd):nth-child(n + 25):nth-child(-n + 32),
    .chessboard > div:nth-child(even):nth-child(n + 17):nth-child(-n + 24),
    .chessboard > div:nth-child(odd):nth-child(n + 41):nth-child(-n + 48),
    .chessboard > div:nth-child(even):nth-child(n + 33):nth-child(-n + 40),
    .chessboard > div:nth-child(odd):nth-child(n + 57):nth-child(-n + 64),
    .chessboard > div:nth-child(even):nth-child(n + 49):nth-child(-n + 56) {
        background: #444;
    }
</style>

<div class="chessboard" id="chessboard1">
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>

    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>

    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>

    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>

    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>

    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>

    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>

    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
    <div data-piece=""></div>
</div>

<script>
    // -- CONFIG -- //
    const piecesMap = {
        'p': 'bP', 'r': 'bR', 'n': 'bN', 'b': 'bB', 'q': 'bQ', 'k': 'bK',
        'P': 'wP', 'R': 'wR', 'N': 'wN', 'B': 'wB', 'Q': 'wQ', 'K': 'wK'
    };
    const reversePiecesMap = Object.fromEntries(
        Object.entries(piecesMap).map(([key, value]) => [value, key])
    );
    const pieceSvgs = {
      'wP': '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45 45"><path d="M22.5 9c-2.21 0-4 1.79-4 4 0 .89.29 1.71.78 2.38C17.33 16.5 16 18.59 16 21c0 2.03.94 3.84 2.41 5.03-3 1.06-7.41 5.55-7.41 13.47h23c0-7.92-4.41-12.41-7.41-13.47 1.47-1.19 2.41-3 2.41-5.03 0-2.41-1.33-4.5-3.28-5.62.49-.67.78-1.49.78-2.38 0-2.21-1.79-4-4-4z" fill="#fff" stroke="#000" stroke-width="1.5" stroke-linecap="round"/></svg>',
      'wR': '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45 45"><g fill="#fff" stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 39h27v-3H9v3zM12 36v-4h21v4H12zM11 14V9h4v2h5V9h5v2h5V9h4v5" stroke-linecap="butt"/><path d="M34 14l-3 3H14l-3-3"/><path d="M31 17v12.5H14V17" stroke-linecap="butt" stroke-linejoin="miter"/><path d="M31 29.5l1.5 2.5h-20l1.5-2.5"/></g></svg>',
      'wN': '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45 45"><g fill="#fff" stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10c10.5 1 16.5 8 16 29H15c0-9 10-6.5 8-21"/><path d="M24 18c.38 2.91-5.55 7.37-8 9-3 2-2.82 4.34-5 4-1.042-.94 1.41-3.04 0-3-1 0 .19 1.23-1 2-1 0-4.003 1-4-4 0-2 6-12 6-12s1.89-1.9 2-3.5c-.73-.994-.5-2-.5-3 1-1 3 2.5 3 2.5h2s.78-1.992 2.5-3c1 0 1 3 1 3"/></g></svg>',
      'wB': '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45 45"><g fill="#fff" stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><g fill="#fff" stroke-linecap="butt"><path d="M9 36c3.39-.97 10.11.43 13.5-2 3.39 2.43 10.11 1.03 13.5 2 0 0 1.65.54 3 2-.68.97-1.65.99-3 .5-3.39-.97-10.11.46-13.5-1-3.39 1.46-10.11.03-13.5 1-1.35.49-2.32.47-3-.5 1.35-1.46 3-2 3-2z"/><path d="M15 32c2.5 2.5 12.5 2.5 15 0 .5-1.5 0-2 0-2 0-2.5-2.5-4-2.5-4 5.5-1.5 6-11.5-5-15.5-11 4-10.5 14-5 15.5 0 0-2.5 1.5-2.5 4 0 0-.5.5 0 2z"/><path d="M25 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 1 1 5 0z"/></g><path d="M17.5 26h10M15 30h15m-7.5-14.5v5M20 18h5" stroke-linejoin="miter"/></g></svg>',
      'wQ': '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45 45"><g fill="#fff" stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 26c8.5-1.5 21-1.5 27 0l2.5-12.5L31 25l-.3-14.1-5.2 13.6-3-14.5-3 14.5-5.2-13.6L14 25 6.5 13.5 9 26z"/><path d="M9 26c0 2 1.5 2 2.5 4 1 1.5 1 1 .5 3.5-1.5 1-1 2.5-1 2.5-1.5 1.5 0 2.5 0 2.5 6.5 1 16.5 1 23 0 0 0 1.5-1 0-2.5 0 0 .5-1.5-1-2.5-.5-2.5-.5-2 .5-3.5 1-2 2.5-2 2.5-4-8.5-1.5-18.5-1.5-27 0z" stroke-linecap="butt"/><path d="M11.5 30c3.5-1 18.5-1 22 0M12 33.5c6-1 15-1 21 0" fill="none"/></g></svg>',
      'wK': '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45 45"><g fill="#fff" stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22.5 11.63V6M20 8h5" stroke-linejoin="miter"/><path d="M22.5 25s4.5-7.5 3-10.5c0 0-1-2.5-3-2.5s-3 2.5-3 2.5c-1.5 3 3 10.5 3 10.5" fill="#fff" stroke-linecap="butt" stroke-linejoin="miter"/><path d="M12.5 37c5.5 3.5 15.5 3.5 21 0v-7s9-4.5 6-10.5c-4-6.5-13.5-3.5-16 4V27v-3.5c-2.5-7.5-12-10.5-16-4-3 6 6 10.5 6 10.5v7"/><path d="M12.5 30c5.5-3 15.5-3 21 0m-21 3.5c5.5-3 15.5-3 21 0m-21 3.5c5.5-3 15.5-3 21 0" fill="none"/></g></svg>',
      'bP': '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45 45"><path d="M22.5 9c-2.21 0-4 1.79-4 4 0 .89.29 1.71.78 2.38C17.33 16.5 16 18.59 16 21c0 2.03.94 3.84 2.41 5.03-3 1.06-7.41 5.55-7.41 13.47h23c0-7.92-4.41-12.41-7.41-13.47 1.47-1.19 2.41-3 2.41-5.03 0-2.41-1.33-4.5-3.28-5.62.49-.67.78-1.49.78-2.38 0-2.21-1.79-4-4-4z" fill="#000" stroke="#000" stroke-width="1.5" stroke-linecap="round"/></svg>',
      'bR': '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45 45"><g fill="#000" stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 39h27v-3H9v3zM12.5 32l1.5-2.5h17l1.5 2.5h-20zM12 36v-4h21v4H12z" stroke-linecap="butt"/><path d="M14 29.5v-13h17v13H14z" stroke-linecap="butt" stroke-linejoin="miter"/><path d="M14 16.5L11 14h23l-3 2.5H14zM11 14V9h4v2h5V9h5v2h5V9h4v5H11z" stroke-linecap="butt"/></g></svg>',
      'bN': '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45 45"><g fill="#000" stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10c10.5 1 16.5 8 16 29H15c0-9 10-6.5 8-21"/><path d="M24 18c.38 2.91-5.55 7.37-8 9-3 2-2.82 4.34-5 4-1.042-.94 1.41-3.04 0-3-1 0 .19 1.23-1 2-1 0-4.003 1-4-4 0-2 6-12 6-12s1.89-1.9 2-3.5c-.73-.994-.5-2-.5-3 1-1 3 2.5 3 2.5h2s.78-1.992 2.5-3c1 0 1 3 1 3"/></g></svg>',
      'bB': '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45 45"><g fill="#000" ><g fill="#000" stroke-linecap="butt"><path d="M9 36c3.39-.97 10.11.43 13.5-2 3.39 2.43 10.11 1.03 13.5 2 0 0 1.65.54 3 2-.68.97-1.65.99-3 .5-3.39-.97-10.11.46-13.5-1-3.39 1.46-10.11.03-13.5 1-1.35.49-2.32.47-3-.5 1.35-1.46 3-2 3-2z"/><path d="M15 32c2.5 2.5 12.5 2.5 15 0 .5-1.5 0-2 0-2 0-2.5-2.5-4-2.5-4 5.5-1.5 6-11.5-5-15.5-11 4-10.5 14-5 15.5 0 0-2.5 1.5-2.5 4 0 0-.5.5 0 2z"/> <path d="M25 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 1 1 5 0z"/></g><g stroke="#fff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path  d="M17.5 26h10M15 30h15m-7.5-14.5v5M20 18h5" stroke-linejoin="miter"/></g></g></svg>',
      'bQ': '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45 45"><g fill="#000" stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 26c8.5-1.5 21-1.5 27 0l2.5-12.5L31 25l-.3-14.1-5.2 13.6-3-14.5-3 14.5-5.2-13.6L14 25 6.5 13.5 9 26z"/><path d="M9 26c0 2 1.5 2 2.5 4 1 1.5 1 1 .5 3.5-1.5 1-1 2.5-1 2.5-1.5 1.5 0 2.5 0 2.5 6.5 1 16.5 1 23 0 0 0 1.5-1 0-2.5 0 0 .5-1.5-1-2.5-.5-2.5-.5-2 .5-3.5 1-2 2.5-2 2.5-4-8.5-1.5-18.5-1.5-27 0z" stroke-linecap="butt"/><path d="M11.5 30c3.5-1 18.5-1 22 0M12 33.5c6-1 15-1 21 0" fill="none" stroke="#fff"/><circle cx="6" cy="12" r="2"/><circle cx="14" cy="9" r="2"/><circle cx="22.5" cy="8" r="2"/><circle cx="31" cy="9" r="2"/><circle cx="39" cy="12" r="2"/></g></svg>',
      'bK': '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 45 45"><g fill="#000" stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22.5 11.63V6M20 8h5" stroke-linejoin="miter"/><path d="M22.5 25s4.5-7.5 3-10.5c0 0-1-2.5-3-2.5s-3 2.5-3 2.5c-1.5 3 3 10.5 3 10.5" fill="#000" stroke-linecap="butt" stroke-linejoin="miter"/><path d="M12.5 37c5.5 3.5 15.5 3.5 21 0v-7s9-4.5 6-10.5c-4-6.5-13.5-3.5-16 4V27v-3.5c-2.5-7.5-12-10.5-16-4-3 6 6 10.5 6 10.5v7"/><path d="M12.5 30c5.5-3 15.5-3 21 0m-21 3.5c5.5-3 15.5-3 21 0m-21 3.5c5.5-3 15.5-3 21 0" fill="none" stroke="#fff"/></g></svg>'
    };
    // :id doit etre remplacer par l'id de la game
    const URL_GET_FEN = { url: '<?= APP_PATH ?>/game/:id/get-position', method: 'GET', argument: '' };
    const URL_GET_STATUS = { url: '<?= APP_PATH ?>/game/:id/get-status', method: 'GET', argument: '' };
    const URL_UPDATE_POSITION = { url: '<?= APP_PATH ?>/game/:id/update-position', method: 'POST', argument: 'fen' };
    const DEFAULT_FEN = '<?= DEFAULT_FEN ?>';
    const GAME_ID = 5;
    const PLAYER = 'w';


    class ChessGame {
      constructor (gameId, player, HtmlSelector = '#chessboard') {
        this.gameId = gameId
        this.player = player
        this.HtmlSelector = HtmlSelector
        this.initialize()
      }

      async initialize () {
        if (!this.fen) {
          await this.getFen()
          this.parseFen()
          this.HtmlToDom()
        }
      }
      S

      setFen (fen) {
        this.fen = fen
        this.parseFen()
      }

      async getFen (id = null) {
        try {
          const response = await fetch(
            `${URL_GET_FEN.url.replace(':id', this.gameId)}${id ? `?fen_id=${id}` : ''}`,
            {
              method: URL_GET_FEN.method,
            }
          )

          if (!response.ok) {
            throw new Error('Erreur lors de la récupération du FEN')
          }
          const data = await response.json()
          this.fen = data.FEN
        } catch (error) {
          console.error('Erreur dans getFen:', error)
        }
        this.parseFen()
      }

      async getStatus () {
        try {
          const response = await fetch(URL_GET_STATUS.url.replace(':id', this.gameId), {
            method: URL_GET_STATUS.method
          })
          if (!response.ok) {
            throw new Error('Erreur lors de la récupération du statut')
          }
          const data = await response.json()
          return data.status // Ce que retourne l'API
        } catch (error) {
          console.error('Erreur dans getStatus:', error)
          return null
        }
      }

      async updatePosition(newFen) {
        // Vérification des données avant d'envoyer la requête
        if (!newFen || typeof newFen !== 'string') {
          console.error('FEN invalide :', newFen);
          return null;
        }

        try {
          const response = await fetch(URL_UPDATE_POSITION.url.replace(':id', this.gameId), {
            method: URL_UPDATE_POSITION.method,
            headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
              fen: newFen,
            }),
          });
          if (!response.ok) {
            console.error('Erreur lors de la requête :', response.status, response.statusText);
            throw new Error('Erreur lors de la mise à jour de la position');
          }

          const data = await response.json();

          if (data.error) {
            console.error('Erreur renvoyée par l\'API :', data.error);
            throw new Error(data.error);
          }

          return data; // Retour des données de l'API
        } catch (error) {
          // Gestion des erreurs
          console.error('Une erreur est survenue dans updatePosition :', error);
          return null;
        }
      }

      parseFen () {
        if (!this.fen || typeof this.fen !== 'string') {
          return
        }

        let parts = this.fen.split(' ')
        this.ranks = parts[0].split('/')
        this.trait = parts[1]
        this.roques = parts[2]
        this.enPassant = parts[3]
        this.coupsDerniereCapture = parseInt(parts[4], 10)
        this.coupsTotaux = parseInt(parts[5], 10)
      }

      ranksToHtml () {
        let result = ''
        let row = 8 // Les rangs commencent par le bas (8 en notation échiquéenne)

        for (let rank of this.ranks) {
          let col = 0 // Les colonnes commencent à 'a'

          for (let char of rank) {
            if (char >= '1' && char <= '8') {
              let emptySquares = parseInt(char, 10)
              for (let i = 0; i < emptySquares; i++) {
                let position = `${String.fromCharCode(97 + col)}${row}` // Convertir l'index de colonne en lettre
                result += `<div data-pos="${position}" data-piece=""></div>`
                col++
              }
            } else {
              // Ajoute une case avec une pièce
              const piece = piecesMap[char]
              let position = `${String.fromCharCode(97 + col)}${row}`
              result += `<div data-pos="${position}" data-piece="${piece}">${pieceSvgs[piece]}</div>`
              col++
            }
          }

          row-- // Les rangs diminuent à chaque itération
        }
        return result
      }

      HtmlToDom () {
        document.querySelectorAll(this.HtmlSelector).forEach((element) => {
          element.innerHTML = this.ranksToHtml()
        })
        this.OnDomUpdate()
      }

      //--GAME EXPERIENCE--//

      async refreshBoard () {
        await this.getFen()
        this.HtmlToDom()
      }

      makeMove () {
        if (this.player === this.trait) {
          this.P = null
          const squares = document.querySelectorAll(this.HtmlSelector + ' > div')
          squares.forEach((square) => {
            square.addEventListener('click', () => {
              const pieceName = square.getAttribute('data-piece')
              if (pieceName !== '') {
                this.P = square
              } else if (this.P !== null && this.P.getAttribute('data-pos') !== square.getAttribute('data-pos')) {
                this.updateFen(this.P, square)
              }
            })
          })
        }
      }

      async updateFen (fromSquare, toSquare) {

        let Piece = fromSquare.getAttribute('data-piece')
        let FromPos = fromSquare.getAttribute('data-pos')
        let ToPos = toSquare.getAttribute('data-pos')

        console.log(Piece, FromPos, ToPos)

        const ltr = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h']

        let ranksBreaked = game.ranks.map(rank =>
          rank.replace(/\d/g, match => '1'.repeat(parseInt(match, 10)))
        )

        let matrix = ranksBreaked.map(rank => Array.from(rank))

        matrix.forEach((rankArray, rowIndex) => {
          rankArray.forEach((square, colIndex) => {
            const currentPos = ltr[colIndex] + (8 - rowIndex)

            if (currentPos === FromPos) {
              matrix[rowIndex][colIndex] = '1'
            } else if (currentPos === ToPos) {
              matrix[rowIndex][colIndex] = reversePiecesMap[Piece] || Piece
            }
          })
        })

        let ranks = matrix.map(rankArray => rankArray.join('')).join('/')
        let newFen = `${this.reformatFenRow(ranks)} ${this.trait} ${this.roques} ${this.enPassant} ${this.coupsDerniereCapture} ${this.coupsTotaux}`

        if(await this.updatePosition(newFen) !== null) {
            this.setFen(newFen)
            this.HtmlToDom()
            //this.refreshBoard();
        }
      }

      reformatFenRow (row) {
        return row.replace(/1+/g, match => String(match.length))
      }

      OnDomUpdate () {
        this.ConsoleLogPiecesNameOnClick()
        this.makeMove()
      }

      ConsoleLogPiecesNameOnClick () {
        // Récupérer les cases de l'échiquier
        const squares = document.querySelectorAll(this.HtmlSelector + ' > div')
        squares.forEach(square => {
          // Ajouter un événement 'click' pour chaque case
          square.addEventListener('click', () => {
            const pieceName = square.getAttribute('data-piece') // Récupère le nom de la pièce
            if (pieceName) {
            } else {
            }
          })
        })
      }

      highlightPossibleMoves (piece, position) {
        // TODO: Mettre en surbrillance les mouvements possibles pour une pièce donnée
      }

      async showGameHistory () {
        // TODO: Afficher l'historique des coups joués
      }

      resetGame () {
        // TODO: Réinitialiser la partie à son état initial
      }
    }

    //--INIT--/
    // Initialisation d'un objet ChessGame
    const game = new ChessGame(GAME_ID, PLAYER,'#chessboard1');
    console.log(game);


    //--EVENT--//
    document.addEventListener('DOMContentLoaded', () => {
        console.log("DOM fully loaded and parsed");
        game.ConsoleLogPiecesNameOnClick()
    });
</script>