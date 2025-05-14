const mongoose = require('mongoose');

const projectSchema = new mongoose.Schema({
  naziv: { type: String, required: true },
  opis: { type: String, required: true },
  cijena: { type: Number, required: true },
  obavljeni_poslovi: { type: String },
  datum_pocetka: { type: Date, required: true },
  datum_zavrsetka: { type: Date, required: true },
  tim: [{ type: mongoose.Schema.Types.ObjectId, ref: 'User' }],  // Polje za članove tima
  arhiviran: { type: Boolean, default: false },  // Polje za arhiviranje

  createdBy: { type: mongoose.Schema.Types.ObjectId, ref: 'User', required: true } // 👈 Dodano ovo
});

module.exports = mongoose.model('Project', projectSchema);
