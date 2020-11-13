var express = require('express');
var router = express.Router();
var userService = require('../services/user-service');

// middleware function to check for logged-in users
var sessionChecker = (req, res, next) => {
  if (req.session.accessToken) {
    res.redirect('/home');
  } else {
    next();
  }    
};

/* GET home page. */
router.get('/', sessionChecker, function(req, res, next) {
  res.render('index', { title: 'Login' });
});

router.get('/home', async function(req, res, next) {
  if (req.session.accessToken) {
    var bodyRequest = req.body;
    bodyRequest.offset = 0;
    bodyRequest.limit = 9;
    try {
      var bodyResp = await userService.listar(bodyRequest);
      bodyResp.title = 'Página Inicial';
      res.render('home', bodyResp);
    } catch (err) {
      res.render('home', { title: 'Página Inicial' });
    }
  } else {
    res.redirect('/');
  }
});

router.post('/signin', async function(req, res, next) {
  var responseBody = {};
  try {
    responseBody = await userService.login(req.body);
    req.session.email = req.body.emailUsuario;
    req.session.accessToken = responseBody.accessToken;
    responseBody.url = '/home';
    responseBody.class = 'success';
    responseBody.successMsg = '';
    responseBody.successTime = 1;
  } catch (err) {
    responseBody.class = 'error';
    responseBody.msg = err.message ? err.message : err;
  }
  res.send(responseBody);
});

router.get('/logout',(req,res) => {
  req.session.destroy((err) => {
    res.redirect('/');
  });
});

module.exports = router;
