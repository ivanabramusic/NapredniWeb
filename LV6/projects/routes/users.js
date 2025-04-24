// routes/projects.js
const express = require('express');
const router = express.Router();
const Project = require('../models/project');
const User = require('../models/user');  // Uvezi User model

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
router.post('/:id/delete', async (req, res) => {
  const project = await Project.findByIdAndDelete(req.params.id);
  if (!project) {
    return res.status(404).send('Projekt nije pronađen');
  }
  res.redirect('/projects');
});

// Dodaj korisnika u tim projekta
router.post('/:id/addMember', async (req, res) => {
  const projectId = req.params.id;
  const userId = req.body.userId;  // ID korisnika kojeg dodaješ u tim

  try {
    const project = await Project.findById(projectId);
    if (!project) return res.status(404).send('Project not found');
    
    project.tim.push(userId);  // Dodaj korisnika u tim
    await project.save();
    
    res.redirect(`/projects/${projectId}`);
  } catch (error) {
    res.status(500).send('Server error');
  }
});

module.exports = router;
