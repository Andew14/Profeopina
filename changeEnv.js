const fs = require('fs');
const dotenv = require('dotenv');

// Cargar las variables de entorno existentes
dotenv.config();

const newEnvVariables = {
    DB_HOST: '127.0.0.1',
    DB_PORT: '3306',
    DB_DATABASE: 'ferteria',
    DB_USERNAME: 'root',
    DB_PASSWORD: ''
};

// Leer el archivo .env
fs.readFile('.env', 'utf8', (err, data) => {
    if (err) {
        return console.error(err);
    }

    let envVariables = data.split('\n');

    // Modificar las variables de entorno
    envVariables = envVariables.map((line) => {
        const [key, value] = line.split('=');
        if (newEnvVariables[key] !== undefined) {
            return `${key}=${newEnvVariables[key]}`;
        }
        return line;
    });

    // Escribir los cambios en el archivo .env
    fs.writeFile('.env', envVariables.join('\n'), (err) => {
        if (err) {
            return console.error(err);
        }
        console.log('Archivo .env actualizado exitosamente.');
    });
});
