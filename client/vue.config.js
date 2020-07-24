module.exports = {
  "outputDir": "../public/app",
  "publicPath": "/app",
  "pages": {
    "app1": {
      "entry": "src/app/main.js",
      "template": "templates/base.html",
      "filename": "../../resources/views/spa/app.blade.php"
    },
    "todo": {
      "entry": "src/todo/main.js",
      "template": "templates/base.html",
      "filename": "../../resources/views/spa/todo.blade.php"
    }
  },
  "transpileDependencies": [
    "vuetify"
  ]
}