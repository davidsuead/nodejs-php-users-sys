var express = require('express');
var router = express.Router();
var userService = require('../services/user-service');
var multer  = require('multer')
var upload = multer({ dest: 'uploads/' })
var fs = require('fs');

// middleware function to check for logged-in users
var sessionChecker = (req, res, next) => {
  if (!req.session.accessToken) {
    res.redirect('/');
  } else {
    next();
  }    
};

router.get('/signup', function(req, res, next) {
  res.render('signup', { title: 'Cadastrar Usuário' });
});

router.post('/create', async function(req, res, next) {
  let responseBody = { class: 'success' };
  try {
    responseBody = await userService.createUser(req.body);
    responseBody.url = '/';
    responseBody.class = 'success';
    responseBody.successMsg = 'Usuário cadastrado com sucesso! Aguarde, você está será redirecionado...';
    responseBody.successTime = 3000;
  } catch (err) {
    responseBody.class = 'error';
    responseBody.msg = err.message ? err.message : err;
  }
  res.send(responseBody);
});

router.get('/edit', sessionChecker, async function(req, res, next) {
  try {
    responseBody = await userService.getByEmail(req.session.email);
    res.render('edit', { title: 'Editar Dados', usuario: responseBody.usuarios[0], errorMsg: '' });
  } catch (err) {
    res.render('edit', { title: 'Editar Dados', errorMsg: err.message ? err.message : err });
  }
});

router.post('/save', sessionChecker, async function(req, res, next) {
  let responseBody = { class: 'success' };
  try {
    responseBody.usuarios = await userService.saveData(req.body);
    responseBody.usuario = responseBody.usuarios[0];
    responseBody.url = '/users/edit';
    responseBody.class = 'success';
    responseBody.successMsg = 'Operação realizada com sucesso!';
    responseBody.successTime = 2000;
    res.send(responseBody);
  } catch (err) {
    responseBody.class = 'error';
    responseBody.msg = err.message ? err.message : err;
    res.send(responseBody);
  }
});

router.get('/biometria', sessionChecker, async function(req, res, next) {
  res.render('biometria', { title: 'Biometria' });
});

router.post('/uploadBiometria', upload.single('fileUploaded'), async function(req, res, next) {
  fs.readFile(req.file.path, 'utf8', async function(err, contents) {
    let responseBody = { class: 'success' };
    try {
      let responseServer = await userService.uploadBiometria(req.session.accessToken, req.file.originalname, contents);
      responseBody.url = '/users/biometria';
      responseBody.class = 'success';
      responseBody.successMsg = responseServer.message;
      responseBody.successTime = 2000;
      res.send(responseBody);
    } catch (err) {
      responseBody.class = 'error';
      responseBody.msg = err.message ? err.message : err;
      res.send(responseBody);
    }
  });
});

module.exports = router;
