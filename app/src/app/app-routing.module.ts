import { LoginGuard } from './guards/login.guard';
import { AuthGuard } from './guards/auth.guard';
import { NgModule } from '@angular/core';
import { PreloadAllModules, RouterModule, Routes } from '@angular/router';

const routes: Routes = [
  {
    path: '',
    redirectTo: 'login',
    pathMatch: 'full'
  },
  {
    path: 'login',
    loadChildren: () => import('./pages/login/login.module').then( m => m.LoginPageModule),
    canActivate: [LoginGuard]
  },
  {
    path: 'home',
    loadChildren: () => import('./pages/home/home.module').then( m => m.HomePageModule),
    canActivate: [AuthGuard]
  },
  {
    path: 'new-user',
    loadChildren: () => import('./pages/user-profile/user-profile.module').then( m => m.UserProfilePageModule),
    canActivate: [AuthGuard]

  },
  {
    path: 'user-profile',
    loadChildren: () => import('./pages/user-profile/user-profile.module').then( m => m.UserProfilePageModule),
    canActivate: [AuthGuard]
  },
  {
    path: 'professionals',
    loadChildren: () => import('./pages/professionals/professionals.module').then( m => m.ProfessionalsPageModule),
    canActivate: [AuthGuard]
  },
  {
    path: 'notes',
    loadChildren: () => import('./pages/notes/notes.module').then( m => m.NotesPageModule),
    canActivate: [AuthGuard]
  },
  {
    path: 'consults',
    loadChildren: () => import('./pages/consults/consults.module').then( m => m.ConsultsPageModule),
    canActivate: [AuthGuard]
  },
  {
    path: 'exams',
    loadChildren: () => import('./pages/exams/exams.module').then( m => m.ExamsPageModule),
    canActivate: [AuthGuard]
  },
  {
    path: 'diseases',
    loadChildren: () => import('./pages/diseases/diseases.module').then( m => m.DiseasesPageModule),
    canActivate: [AuthGuard]
  },
  {
    path: 'medications',
    loadChildren: () => import('./pages/medications/medications.module').then( m => m.MedicationsPageModule),
    canActivate: [AuthGuard]
  },
];

@NgModule({
  imports: [
    RouterModule.forRoot(routes, { preloadingStrategy: PreloadAllModules })
  ],
  exports: [RouterModule]
})
export class AppRoutingModule { }
