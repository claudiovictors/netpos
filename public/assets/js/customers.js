/**
 * ============================================================================
 * NETPOS - JS/POS/CUSTOMERS.JS
 * Gestão de clientes mock do POS com suporte a pesquisa e criação rápida.
 * ============================================================================
 */

(function (window) {
  'use strict';

  var mockCustomers = [
    {
      id: 1,
      name: 'Consumidor Final',
      nif: '999999999',
      phone: '—',
      email: '—',
      address: 'Luanda, Angola',
      balance: 0.00,
      points: 0,
      isFinalConsumer: true
    },
    {
      id: 2,
      name: 'Roquita Isilda Pimentel',
      nif: '580436640',
      phone: '+244 923 111 222',
      email: 'roquita.pimentel@exemplo.ao',
      address: 'Maianga, Luanda',
      balance: 0.00,
      points: 120,
      isFinalConsumer: false
    },
    {
      id: 3,
      name: 'Kwanza Distribuidora Lda',
      nif: '541289301',
      phone: '+244 912 345 678',
      email: 'comercial@kwanza-distrib.ao',
      address: 'Viana Park, Luanda',
      balance: 50000.00,
      points: 450,
      isFinalConsumer: false
    },
    {
      id: 4,
      name: 'Manuel dos Santos',
      nif: '510982344',
      phone: '+244 931 445 566',
      email: 'm.santos@exemplo.ao',
      address: 'Talatona, Luanda',
      balance: 0.00,
      points: 45,
      isFinalConsumer: false
    },
    {
      id: 5,
      name: 'Teresa Muondo',
      nif: '530491823',
      phone: '+244 945 778 899',
      email: 'teresa.muondo@exemplo.ao',
      address: 'Benfica, Luanda',
      balance: 12500.00,
      points: 80,
      isFinalConsumer: false
    }
  ];

  var PosCustomers = {
    getAll: function () {
      return mockCustomers;
    },

    getFinalConsumer: function () {
      return mockCustomers[0];
    },

    findById: function (id) {
      return mockCustomers.find(function (c) {
        return c.id === parseInt(id, 10);
      }) || mockCustomers[0];
    },

    search: function (term) {
      if (!term || !term.trim()) return mockCustomers;
      var clean = term.trim().toLowerCase();
      return mockCustomers.filter(function (c) {
        return c.name.toLowerCase().includes(clean) || c.nif.includes(clean) || c.phone.includes(clean);
      });
    },

    createCustomer: function (data) {
      var newId = mockCustomers.length + 1;
      var newCustomer = {
        id: newId,
        name: data.name.trim(),
        nif: (data.nif || '999999999').trim(),
        phone: (data.phone || '—').trim(),
        email: (data.email || '—').trim(),
        address: (data.address || 'Luanda, Angola').trim(),
        balance: 0.00,
        points: 0,
        isFinalConsumer: false
      };
      mockCustomers.push(newCustomer);
      return newCustomer;
    }
  };

  window.PosCustomers = PosCustomers;
})(window);
