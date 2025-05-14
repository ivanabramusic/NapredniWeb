const express = require('express');
const router = express.Router();
const User = require('../models/User');
const bcrypt = require('bcryptjs');

// GET register page
router.get('/register', (req, res) => {
  res.render('register');
});

// POST register form
router.post('/register', async (req, res) => {
  const { name, email, password } = req.body;

  try {
    // Provjera da li korisnik već postoji
    const existingUser = await User.findOne({ email });
    if (existingUser) {
      return res.render('register', { message: 'Korisnik već postoji' });  // Prikazuje poruku na stranici
    }

    // Kreiranje novog korisnika
    const newUser = new User({ name, email, password });
    await newUser.save();

    // Automatska prijava korisnika
    req.session.user = newUser;

    // Preusmjeravanje na projekte
    res.redirect('/projects');
  } catch (err) {
    console.error(err);
    res.status(500).send('Greška pri registraciji');
  }
});

// GET login page
router.get('/login', (req, res) => {
  res.render('login');
});

// POST login form
router.post('/login', async (req, res) => {
  const { email, password } = req.body;

  try {
    // Provjera korisnika
    const user = await User.findOne({ email });
    if (!user) {
      return res.render('login', { message: 'Ne postoji korisnik s tim emailom' });  // Prikazuje poruku na login stranici
    }

    // Provjera lozinke
    const isMatch = await user.comparePassword(password);
    if (!isMatch) {
      return res.render('login', { message: 'Neispravna lozinka' });
    }

    // Spremi korisnika u sesiju
    req.session.user = user;

    // Preusmjeravanje na projekte
    res.redirect('/projects');
  } catch (err) {
    console.error(err);
    res.status(500).send('Greška pri prijavi');
  }
});

// GET logout
router.get('/logout', (req, res) => {
  req.session.destroy((err) => {
    if (err) {
      return res.status(500).send('Greška pri logoutu');
    }
    res.redirect('/auth/login');  // Preusmjerava na login stranicu nakon logouta
  });
});





module.exports = router;
