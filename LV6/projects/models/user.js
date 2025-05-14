const mongoose = require('mongoose');
const bcrypt = require('bcryptjs');

// Definicija korisničkog shema
const userSchema = new mongoose.Schema({
  name: { 
    type: String, 
    required: true 
  },
  email: { 
    type: String, 
    required: true, 
    unique: true, 
    validate: {
      validator: function(v) {
        return /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/.test(v);  // Regex za provjeru e-maila
      },
      message: props => `${props.value} nije validna e-mail adresa!`
    }
  },
  password: { 
    type: String, 
    required: true 
  },
  role: { 
    type: String, 
    enum: ['admin', 'user'], 
    default: 'user' 
  },
  createdAt: { 
    type: Date, 
    default: Date.now 
  }
});

// Hashiranje lozinke prije spremanja korisnika
userSchema.pre('save', async function(next) {
  if (!this.isModified('password')) return next();  // Ako lozinka nije modificirana, ne radi ništa

  try {
    const salt = await bcrypt.genSalt(10);  // Generiraj sol za hashiranje
    this.password = await bcrypt.hash(this.password, salt);  // Hashiraj lozinku
    next();
  } catch (error) {
    next(error);
  }
});

// Metoda za provjeru lozinke
userSchema.methods.comparePassword = async function(candidatePassword) {
  try {
    return await bcrypt.compare(candidatePassword, this.password);  // Provjeri lozinku
  } catch (error) {
    throw error;
  }
};

module.exports = mongoose.models.User || mongoose.model('User', userSchema);
