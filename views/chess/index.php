<div>
    <form action="<?= APP_PATH ?>/game/create-game" method="POST">
        <label for="opponent_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Opponent ID</label>
        <input type="text" id="opponent_id" name="opponent_id"
               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 mb-4 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
               required>
        <button type="submit" id="createGame"
                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            Create Game
        </button>
    </form>
</div>