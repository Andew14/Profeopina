const { JSDOM } = require('jsdom');

describe('Example Test Suite', () => {
  // Configurar el DOM simulado antes de cada prueba
  beforeEach(() => {
    const dom = new JSDOM('<!doctype html><html><body><div dusk="foo"></div></body></html>');
    global.document = dom.window.document;
  });

  // Ejemplo de prueba
  test('it should set text content of element with dusk="foo"', () => {
    // Verificar si el elemento existe
    const element = document.querySelector('[dusk="foo"]');
    if (element) {
      element.textContent = 'evaluated';
      expect(element.textContent).toBe('evaluated'); // Asegura que el contenido se haya establecido correctamente
    } else {
      throw new Error('Element with dusk="foo" not found');
    }
  });
});
