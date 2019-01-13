const generator = require('webfonts-generator');
const path = require('path');
const fs = require('fs');

var files = [];
var iconsPath = path.resolve(__dirname) + '/icons';
fs.readdirSync(iconsPath).forEach(function(file) {
  files.push(iconsPath + '/' + file);
});

generator({
  files: files,
  dest: 'dist/',
  fontName: 'enhavo-icons',
  html: true
}, function(error) {
  if (error) {
    console.log('Fail!', error);
  } else {
    console.log('Done!');
  }
});