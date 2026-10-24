/**
 * ============================================================================
 * NETPOS - JS/POS/FORMAT.JS
 * Utilitários de formatação em Português de Portugal para o ecrã de vendas:
 * - Moeda em Kwanzas angolanos ("1.850,00 Kz")
 * - Datas completas e relógio em tempo real em pt-PT
 * ============================================================================
 */

(function (window) {
  'use strict';

  var PosFormat = {
    /**
     * Formata um valor numérico para a moeda de Angola ("1.850,00 Kz")
     * @param {number|string} amount
     * @param {boolean} includeSymbol - se deve incluir " Kz" no final
     * @returns {string}
     */
    currency: function (amount, includeSymbol) {
      if (amount === null || amount === undefined || isNaN(amount)) {
        amount = 0;
      }
      var num = typeof amount === 'string' ? parseFloat(amount) : amount;
      
      // Separar parte inteira e decimal com arredondamento comercial a 2 casas
      var fixed = Math.abs(num).toFixed(2);
      var parts = fixed.split('.');
      var integerPart = parts[0];
      var decimalPart = parts[1];

      // Adicionar pontos como separadores de milhares
      var formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
      var sign = num < 0 ? '-' : '';
      var result = sign + formattedInteger + ',' + decimalPart;

      if (includeSymbol !== false) {
        result += ' Kz';
      }

      return result;
    },

    /**
     * Converte uma string formatada em número decimal
     * @param {string} str
     * @returns {number}
     */
    parseCurrency: function (str) {
      if (!str) return 0;
      if (typeof str === 'number') return str;
      var cleaned = str.replace(/[^\d,-]/g, '').replace(',', '.');
      var parsed = parseFloat(cleaned);
      return isNaN(parsed) ? 0 : parsed;
    },

    /**
     * Formata uma data completa por extenso em português de Portugal
     * Exemplo: "quinta-feira, 22 de outubro de 2026 12:00"
     * @param {Date} date
     * @returns {string}
     */
    fullDatePt: function (date) {
      if (!date) date = new Date();
      
      var diasSemana = [
        'domingo', 'segunda-feira', 'terça-feira', 'quarta-feira',
        'quinta-feira', 'sexta-feira', 'sábado'
      ];
      
      var meses = [
        'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho',
        'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'
      ];

      var diaSemana = diasSemana[date.getDay()];
      var dia = date.getDate();
      var mes = meses[date.getMonth()];
      var ano = date.getFullYear();
      var horas = String(date.getHours()).padStart(2, '0');
      var minutos = String(date.getMinutes()).padStart(2, '0');
      var segundos = String(date.getSeconds()).padStart(2, '0');

      return diaSemana + ', ' + dia + ' de ' + mes + ' de ' + ano + ' ' + horas + ':' + minutos + ':' + segundos;
    },

    /**
     * Formata apenas a hora (HH:MM)
     * @param {Date} date
     * @returns {string}
     */
    timeShort: function (date) {
      if (!date) date = new Date();
      var horas = String(date.getHours()).padStart(2, '0');
      var minutos = String(date.getMinutes()).padStart(2, '0');
      return horas + ':' + minutos;
    }
  };

  // Exportar globalmente para a aplicação POS
  window.PosFormat = PosFormat;
})(window);
