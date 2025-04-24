const express = require('express');
const router = express.Router();
const Project = require('../models/project');
const User = require('../models/user'); // Uvoz modela korisnika

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
  await Project.create(req.body);
  res.redirect('/projects');
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

// Prikaz forme za dodavanje člana tima
router.get('/:id/addMember', async (req, res) => {
  const project = await Project.findById(req.params.id);
  if (!project) {
    return res.status(404).send('Projekt nije pronađen');
  }
  const users = await User.find();  // Dohvati sve korisnike
  res.render('addMember', { project, users });
});


router.post('/:id/addMember', async (req, res) => {
  const projectId = req.params.id;
  const userId = req.body.userId;  // ID korisnika kojeg dodajete u tim

  try {
    const project = await Project.findById(projectId);
    if (!project) return res.status(404).send('Projekt nije pronađen');
    
    project.tim.push(userId);  // Dodaj korisnika u tim
    await project.save();
    
    res.redirect(`/projects/${projectId}`);  // Preusmjerite korisnika na stranicu projekta
  } catch (error) {
    res.status(500).send('Server error');
  }
});





module.exports = router;


