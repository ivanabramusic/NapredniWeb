const express = require('express');
const router = express.Router();
const Project = require('../models/project');
const User = require('../models/User');
const mongoose = require('mongoose');

// Pregled svih projekata
router.get('/', async (req, res) => {
  const projects = await Project.find();
  res.render('projects', { projects });
});

// Forma za dodavanje novog projekta
router.get('/new', (req, res) => {
  res.render('newProject');
});

// Dodavanje novog projekta
router.post('/new', async (req, res) => {
  try {
    await Project.create({
      ...req.body,
      createdBy: req.session.user._id 
    });
    res.redirect('/projects');
  } catch (err) {
    console.error(err);
    res.status(500).send('Greška pri kreiranju projekta');
  }
});

router.get('/archive', async (req, res) => {
  if (!req.session.user) return res.redirect('/auth/login');

  const userId = req.session.user._id;

  const archivedProjects = await Project.find({
    arhiviran: true,
    $or: [
      { createdBy: userId },
      { tim: userId }
    ]
  });

  res.render('archive', { projects: archivedProjects });
});


// Projekti koje je korisnik napravio
router.get('/my-projects', async (req, res) => {
  if (!req.session.user) return res.redirect('/auth/login');  // zaštita

  const myProjects = await Project.find({ createdBy: req.session.user._id });
  res.render('myProjects', { projects: myProjects });
});


// Ruta za projekte na kojima je korisnik član
router.get('/my-member-projects', async (req, res) => {
  if (!req.session.user) {
    return res.redirect('/auth/login');  // Provjera je li korisnik ulogiran
  }

  try {
    // Dohvati projekte na kojima je korisnik član
    const projects = await Project.find({ tim: req.session.user._id });

    // Renderiraj stranicu sa svim projektima na kojima je korisnik član
    res.render('myMemberProjects', { projects });
  } catch (err) {
    console.error(err);
    res.redirect('/projects');  // Ako nešto pođe po zlu, vratit će korisnika na popis svih projekata
  }
});





// Ažuriranje obavljenih poslova
router.post('/:id/updateWorkStatus', async (req, res) => {
  try {
    if (!req.session.user) return res.redirect('/auth/login');

    console.log('Primljeno:', req.body); // vidi što dolazi

    const project = await Project.findById(req.params.id);
    const userId = new mongoose.Types.ObjectId(req.session.user._id);

    const isMember = project.tim.some(memberId => memberId.equals(userId));
    if (!isMember) return res.redirect('/projects/my-member-projects');

    project.obavljeni_poslovi = req.body.obavljeni_poslovi;
    await project.save();

    res.redirect('/projects/my-member-projects');
  } catch (err) {
    console.error(err);
    res.redirect('/projects/my-member-projects');
  }
});



// Prikaz forme za uređivanje projekta
router.get('/:id/edit', async (req, res) => {
  const project = await Project.findById(req.params.id);
  if (!project) {
    return res.status(404).send('Projekt nije pronađen');
  }
  res.render('editProject', { project });
});

// Ažuriranje projekta
router.post('/:id/edit', async (req, res) => {
  const project = await Project.findByIdAndUpdate(req.params.id, req.body, { new: true });
  if (!project) {
    return res.status(404).send('Projekt nije pronađen');
  }
  res.redirect('/projects');
});

// Brisanje projekta
router.post('/:id', async (req, res) => {
  if (req.body._method === 'DELETE') {
    const project = await Project.findByIdAndDelete(req.params.id);
    if (!project) {
      return res.status(404).send('Projekt nije pronađen');
    }
    return res.redirect('/projects');
  }
  res.status(405).send('Method Not Allowed');
});

// Detalji pojedinačnog projekta
router.get('/:id', async (req, res) => {
  const project = await Project.findById(req.params.id).populate('tim');
  if (!project) {
    return res.status(404).send('Projekt nije pronađen');
  }
  res.render('projectDetails', { project });
});


router.get('/:id/addMember', async (req, res) => {
  try {
    const project = await Project.findById(req.params.id).populate('createdBy'); // Provjeriti koji je korisnik stvorio projekt
    const users = await User.find({
      _id: { $n: project.createdBy._id }, // Isključivanje korisnika koji je stvorio projekt
      _id: { $nin: project.tim } // Isključivanje korisnika koji su već u timu
    })

    console.log(project.createdBy);
    // Prikazujemo stranicu za dodavanje članova
    res.render('addMember', { project, users });
  } catch (err) {
    console.error(err);
    res.redirect('/projects'); // Ako nešto pođe po zlu, redirektaj na stranicu s projektima
  }
});



// Route za dodavanje korisnika u tim
router.post('/:id/addMember', async (req, res) => {
  try {
    const project = await Project.findById(req.params.id);
    const userId = req.body.userId;

    // Provjera da korisnik već nije u timu
    if (project.tim.includes(userId)) {
      return res.status(400).send('Korisnik je već član tima');
    }

    // Provjera postoji li korisnik u bazi
    const user = await User.findById(userId);
    if (!user) {
      return res.status(404).send('Korisnik nije pronađen');
    }

    // Dodaj korisnika u tim
    project.tim.push(userId);
    await project.save();

    res.redirect('/projects/my-projects');
  } catch (err) {
    console.error(err);
    res.redirect('/projects');
  }
});











module.exports = router;


