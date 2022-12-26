# Convert Indentation Regex

((( {2})(?: {2}))(\b|(?!=[,'";\.:\*\\\/\{\}\[\]\(\)]))) --> $3 --> 4 to 2 indentation

((( {2}))(\b|(?!=[,'";\.:\\*\\\/{\}\[\]\(\)]))) --> $3$3 --> 2 to 4 indentation

./app, ./config, ./database, ./lang, ./resources, ./routes, ./tests