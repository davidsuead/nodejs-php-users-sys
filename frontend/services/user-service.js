const axios = require('axios');
var jwt = require('jsonwebtoken');

class UserService {

    createUser(body) {
        return new Promise((resolve, reject) => {
            if (body.senhaUsuario == body.senhaUsuarioConfirmar) {
                axios({
                    method: 'post',
                    url: process.env.APP_URL_API + '/createUser',
                    params: body,
                    headers: {
                        authorization: 'Bearer ' + process.env.BEARER
                    }
                })
                .then((res) => {
                    if (res && res.data) {
                        resolve(res.data);
                    } else {
                        reject('Resposta do servidor inesperada');
                    }
                })
                .catch((error) => {
                    if(error.response) {
                        reject(error.response.data);
                    } else if(error.request) {
                        reject('Houve uma falha na comunicação com servidor');
                    } else {
                        reject('Erro inesperado do servidor');
                    }
                });
            } else {
                reject({ message: 'Senhas não conferem' });
            }
        });
    }

    listar(body) {
        return new Promise((resolve, reject) => {
            axios({
                method: 'get',
                url: process.env.APP_URL_API + '/listarUsuarios',
                params: body,
                headers: {
                    authorization: 'Bearer ' + process.env.BEARER
                }
            })
            .then((res) => {
                if (res && res.data) {
                    resolve(res.data);
                } else {
                    reject('Resposta do servidor inesperada');
                }
            })
            .catch((error) => {
                if(error.response) {
                    reject(error.response.data);
                } else if(error.request) {
                    reject('Houve uma falha na comunicação com servidor');
                } else {
                    reject('Erro inesperado do servidor');
                }
            });
        });
    }

    login(body) {
        return new Promise((resolve, reject) => {
            axios({
                method: 'post',
                url: process.env.APP_URL_API + '/login',
                params: body,
                headers: {
                    authorization: 'Bearer ' + process.env.BEARER
                }
            })
            .then((res) => {
                if (res && res.data) {
                    resolve(res.data);
                } else {
                    reject('Resposta do servidor inesperada');
                }
            })
            .catch((error) => {
                if(error.response) {
                    reject(error.response.data);
                } else if(error.request) {
                    reject('Houve uma falha na comunicação com servidor');
                } else {
                    reject('Erro inesperado do servidor');
                }
            });
        });
    }

    getByEmail(emailUsuario)
    {
        return new Promise((resolve, reject) => {
            axios({
                method: 'get',
                url: process.env.APP_URL_API + '/listarUsuarios',
                params: {emailUsuario: emailUsuario},
                headers: {
                    authorization: 'Bearer ' + process.env.BEARER
                }
            })
            .then((res) => {
                if (res && res.data) {
                    resolve(res.data);
                } else {
                    reject('Resposta do servidor inesperada');
                }
            })
            .catch((error) => {
                if(error.response) {
                    reject(error.response.data);
                } else if(error.request) {
                    reject('Houve uma falha na comunicação com servidor');
                } else {
                    reject('Erro inesperado do servidor');
                }
            });
        });
    }

    saveData(body) {
        return new Promise((resolve, reject) => {
            axios({
                method: 'post',
                url: process.env.APP_URL_API + '/editarDados',
                params: body,
                headers: {
                    authorization: 'Bearer ' + process.env.BEARER
                }
            })
            .then((res) => {
                if (res && res.data) {
                    resolve(res.data);
                } else {
                    reject('Resposta do servidor inesperada');
                }
            })
            .catch((error) => {
                if(error.response) {
                    reject(error.response.data);
                } else if(error.request) {
                    reject('Houve uma falha na comunicação com servidor');
                } else {
                    reject('Erro inesperado do servidor');
                }
            });
        });
    }

    uploadBiometria(accessToken, filename, content) {
        return new Promise((resolve, reject) => {
            axios({
                method: 'post',
                url: process.env.APP_URL_API + '/uploadBiometria',
                data: {
                    tokenJwt: jwt.sign({ 
                        accessToken: accessToken,
                        nomeArquivo: filename,
                        conteudoArquivo: Buffer.from(content).toString('base64')                         
                    }, process.env.SECRET_JWT)
                },
                headers: {
                    authorization: 'Bearer ' + process.env.BEARER
                }
            })
            .then((res) => {
                if (res && res.data) {
                    resolve(res.data);
                } else {
                    reject('Resposta do servidor inesperada');
                }
            })
            .catch((error) => {
                if(error.response) {
                    reject(error.response.data);
                } else if(error.request) {
                    reject('Houve uma falha na comunicação com servidor');
                } else {
                    reject('Erro inesperado do servidor');
                }
            });
        });
    }
}

var userServ = new UserService();
module.exports = userServ;