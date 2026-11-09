'use client'

import { useMemo, useState } from 'react'
import {
  AlertCircle,
  BarChart3,
  Bell,
  BookOpen,
  CalendarDays,
  Check,
  ChevronDown,
  ClipboardCheck,
  Download,
  FileText,
  LayoutDashboard,
  Menu,
  MoreHorizontal,
  Search,
  Settings,
  Users,
  X,
  XCircle,
} from 'lucide-react'

type AttendanceStatus = 'Presente' | 'Ausente' | 'Justificado'

type Student = {
  initials: string
  name: string
  id: string
  course: string
  status: AttendanceStatus
  time: string
  absences: number
  tone: string
}

const students: Student[] = [
  { initials: 'EC', name: 'Eliot Daniel Balbuena Cespedes', id: 'A-1024', course: '3ro BTI “A”', status: 'Presente', time: '07:42', absences: 1, tone: 'blue' },
  { initials: 'GM', name: 'Giovanna Mishell Colman Diaz', id: 'A-1025', course: '3ro BTI “A”', status: 'Presente', time: '07:45', absences: 0, tone: 'purple' },
  { initials: 'DD', name: 'Dennis Gabriel Dominguez', id: 'A-1026', course: '3ro BTI “A”', status: 'Justificado', time: '—', absences: 3, tone: 'orange' },
  { initials: 'MF', name: 'María Belén Ferreira Núñez', id: 'A-1027', course: '3ro BTI “A”', status: 'Ausente', time: '—', absences: 5, tone: 'pink' },
  { initials: 'JL', name: 'José Luis López Benítez', id: 'A-1028', course: '3ro BTI “A”', status: 'Presente', time: '07:51', absences: 2, tone: 'green' },
]

const menuItems = [
  { label: 'Panel general', icon: LayoutDashboard, href: 'index.php', active: true },
  { label: 'Asistencias', icon: ClipboardCheck, href: 'asistencias.php' },
  { label: 'Alumnos', icon: Users, href: 'alumnos.php' },
  { label: 'Cursos', icon: BookOpen, href: 'cursos.php' },
  { label: 'Reportes', icon: BarChart3, href: 'reportes.php' },
]

function StatusBadge({ status }: { status: AttendanceStatus }) {
  const styles = {
    Presente: 'status-present',
    Ausente: 'status-absent',
    Justificado: 'status-excused',
  }
  return <span className={`status-badge ${styles[status]}`}><span className="status-dot" />{status}</span>
}

export default function Page() {
  const [mobileOpen, setMobileOpen] = useState(false)
  const [search, setSearch] = useState('')
  const [course, setCourse] = useState('Todos los cursos')
  const [notice, setNotice] = useState('')

  const filteredStudents = useMemo(() => {
    const query = search.toLowerCase().trim()
    return students.filter((student) => {
      const matchesSearch = !query || student.name.toLowerCase().includes(query) || student.id.toLowerCase().includes(query)
      const matchesCourse = course === 'Todos los cursos' || student.course === course
      return matchesSearch && matchesCourse
    })
  }, [search, course])

  const showNotice = (message: string) => {
    setNotice(message)
    window.setTimeout(() => setNotice(''), 2800)
  }

  return (
    <div className="app-shell">
      <aside className={`sidebar ${mobileOpen ? 'sidebar-open' : ''}`}>
        <div className="brand-lockup">
          <img src="https://hebbkx1anhila5yf.public.blob.vercel-storage.com/Krono-NKlfvW1hRY5cdvc1V0pP5yat9FxCwi.png" alt="Krono" className="brand-logo" />
          <div><strong>KRONO</strong><span>Gestión escolar</span></div>
        </div>
        <div className="school-label">COLEGIO PARROQUIAL<br /><strong>SAN AGUSTÍN</strong></div>
        <nav className="sidebar-nav" aria-label="Navegación principal">
          <p className="nav-caption">MENÚ PRINCIPAL</p>
          {menuItems.map(({ label, icon: Icon, href, active }) => (
            <a key={label} href={href} className={`nav-item ${active ? 'nav-active' : ''}`} aria-current={active ? 'page' : undefined}>
              <Icon size={18} strokeWidth={active ? 2.4 : 1.8} /><span>{label}</span>{active && <span className="active-indicator" />}
            </a>
          ))}
          <p className="nav-caption nav-caption-spaced">CONFIGURACIÓN</p>
          <a className="nav-item" href="configuracion.php"><Settings size={18} /><span>Configuración</span></a>
        </nav>
        <div className="sidebar-bottom"><div className="academic-year"><span className="year-dot" />Año lectivo <strong>2026</strong></div><div className="profile-mini"><div className="profile-avatar">AD</div><div><strong>Administrador</strong><span>Secretaría académica</span></div><MoreHorizontal size={17} /></div></div>
      </aside>
      {mobileOpen && <button className="mobile-overlay" aria-label="Cerrar menú" onClick={() => setMobileOpen(false)} />}

      <main className="main-content">
        <header className="topbar">
          <button className="mobile-menu" onClick={() => setMobileOpen(true)} aria-label="Abrir menú"><Menu size={22} /></button>
          <div className="breadcrumb"><span>Panel general</span><span className="breadcrumb-separator">/</span><strong>Resumen de hoy</strong></div>
          <div className="topbar-actions"><button className="icon-button notification-button" aria-label="Notificaciones" onClick={() => showNotice('No tienes notificaciones nuevas')}><Bell size={19} /><span /></button><div className="topbar-date"><CalendarDays size={17} /><span>Martes, 18 de agosto de 2026</span></div></div>
        </header>

        <div className="content-wrap">
          <section className="welcome-row"><div><p className="eyebrow">MARTES, 18 DE AGOSTO DE 2026</p><h1>Buen día, Administrador</h1><p className="subtitle">Este es el resumen de asistencia de tu institución.</p></div><button className="primary-button" onClick={() => showNotice('Nuevo registro de asistencia iniciado')}><ClipboardCheck size={17} />Registrar asistencia</button></section>

          <section className="stats-grid" aria-label="Resumen de asistencia">
            <div className="stat-card stat-blue"><div className="stat-icon"><Users size={20} /></div><div><span className="stat-label">Total de alumnos</span><strong className="stat-number">248</strong><span className="stat-meta positive">↑ 4.2% <em>vs. mes anterior</em></span></div></div>
            <div className="stat-card stat-green"><div className="stat-icon"><Check size={20} /></div><div><span className="stat-label">Presentes hoy</span><strong className="stat-number">231</strong><span className="stat-meta positive">93.1% <em>de asistencia</em></span></div></div>
            <div className="stat-card stat-orange"><div className="stat-icon"><XCircle size={20} /></div><div><span className="stat-label">Ausentes hoy</span><strong className="stat-number">12</strong><span className="stat-meta negative">↑ 2 <em>vs. ayer</em></span></div></div>
            <div className="stat-card stat-purple"><div className="stat-icon"><FileText size={20} /></div><div><span className="stat-label">Justificados</span><strong className="stat-number">5</strong><span className="stat-meta neutral">2.0% <em>del total</em></span></div></div>
          </section>

          <section className="dashboard-grid">
            <div className="panel attendance-panel"><div className="panel-heading"><div><h2>Asistencia de hoy</h2><p>Resumen por curso</p></div><button className="text-button" onClick={() => showNotice('Mostrando todos los registros')}>Ver detalle <span>→</span></button></div><div className="course-list"><CourseRow label="3ro BTI “A”" total="32 alumnos" present={30} percent="93.8%" color="green" /><CourseRow label="3ro BTI “B”" total="29 alumnos" present={27} percent="93.1%" color="blue" /><CourseRow label="2do BTI “A”" total="31 alumnos" present={29} percent="93.5%" color="purple" /><CourseRow label="2do BTI “B”" total="28 alumnos" present={25} percent="89.3%" color="orange" /><CourseRow label="1ro BTI “A”" total="30 alumnos" present={29} percent="96.7%" color="teal" /></div><div className="panel-footer"><div className="legend"><span><i className="legend-dot present" />Presentes</span><span><i className="legend-dot absent" />Ausentes</span><span><i className="legend-dot excused" />Justificados</span></div><span className="updated-label">Actualizado hace 5 min</span></div></div>
            <div className="panel trend-panel"><div className="panel-heading"><div><h2>Tendencia semanal</h2><p>Porcentaje de asistencia</p></div><button className="select-button" onClick={() => showNotice('Filtro semanal seleccionado')}>Esta semana <ChevronDown size={14} /></button></div><div className="chart-area"><div className="chart-y"><span>100%</span><span>75%</span><span>50%</span><span>25%</span><span>0%</span></div><div className="chart"><div className="grid-lines"><i /><i /><i /><i /><i /></div><svg viewBox="0 0 390 150" preserveAspectRatio="none" aria-label="Gráfico de asistencia semanal"><defs><linearGradient id="fill" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stopColor="#5a57d9" stopOpacity=".22" /><stop offset="100%" stopColor="#5a57d9" stopOpacity="0" /></linearGradient></defs><path d="M0,54 C25,44 43,64 69,45 S111,58 138,39 S176,49 207,35 S249,43 276,29 S316,51 344,22 S369,35 390,16 L390,150 L0,150Z" fill="url(#fill)" /><path d="M0,54 C25,44 43,64 69,45 S111,58 138,39 S176,49 207,35 S249,43 276,29 S316,51 344,22 S369,35 390,16" fill="none" stroke="#5956d6" strokeWidth="3" strokeLinecap="round" /></svg><div className="chart-x"><span>Lun</span><span>Mar</span><span>Mié</span><span>Jue</span><span>Vie</span><span>Sáb</span></div></div></div><div className="chart-summary"><div><strong>92.4%</strong><span>Promedio semanal</span></div><span className="trend-up">↑ 3.8% <small>esta semana</small></span></div></div>
          </section>

          <section className="panel records-panel"><div className="panel-heading records-heading"><div><h2>Registros recientes</h2><p>Últimas asistencias registradas</p></div><button className="outline-button" onClick={() => showNotice('Exportando registros en formato CSV')}><Download size={16} />Exportar</button></div><div className="filters"><label className="search-field"><Search size={17} /><input value={search} onChange={(e) => setSearch(e.target.value)} placeholder="Buscar alumno..." aria-label="Buscar alumno" /></label><select value={course} onChange={(e) => setCourse(e.target.value)} aria-label="Filtrar por curso"><option>Todos los cursos</option><option>3ro BTI “A”</option><option>3ro BTI “B”</option><option>2do BTI “A”</option></select><button className="date-filter" onClick={() => showNotice('Selector de fecha disponible en el sistema')}><CalendarDays size={16} />18 ago, 2026<ChevronDown size={14} /></button></div><div className="table-wrap"><table><thead><tr><th>ALUMNO</th><th>CURSO</th><th>ESTADO</th><th>HORA</th><th>INASISTENCIAS</th><th /></tr></thead><tbody>{filteredStudents.map((student) => <tr key={student.id}><td><div className="student-cell"><span className={`student-avatar avatar-${student.tone}`}>{student.initials}</span><div><strong>{student.name}</strong><span>{student.id}</span></div></div></td><td>{student.course}</td><td><StatusBadge status={student.status} /></td><td className="time-cell">{student.time}</td><td><span className={student.absences >= 4 ? 'critical-absences' : ''}>{student.absences}</span>{student.absences >= 4 && <span className="critical-label">Críticas</span>}</td><td><button className="more-button" aria-label={`Más opciones para ${student.name}`} onClick={() => showNotice(`Opciones para ${student.name}`)}><MoreHorizontal size={17} /></button></td></tr>)}</tbody></table>{filteredStudents.length === 0 && <div className="empty-state">No encontramos alumnos con esos filtros.</div>}</div><div className="table-pagination"><span>Mostrando <strong>{filteredStudents.length}</strong> de 248 alumnos</span><div><button disabled>Anterior</button><button className="page-active">1</button><button>2</button><button>3</button><button>...</button><button>25</button><button>Siguiente</button></div></div></section>
          <footer className="institution-footer"><div><strong>KRONO</strong><span>Gestión de asistencia escolar</span></div><p>Alumnos: Eliot Daniel Balbuena Cespedes · Giovanna Mishell Colman Diaz · Dennis Gabriel Dominguez</p><span>3ro Bachiller Técnico en Informática · 2026</span></footer>
        </div>
      </main>
      {notice && <div className="toast" role="status"><Check size={16} />{notice}</div>}
    </div>
  )
}

function CourseRow({ label, total, present, percent, color }: { label: string; total: string; present: number; percent: string; color: string }) {
  return <div className="course-row"><div className="course-title"><span className={`course-marker marker-${color}`} /><div><strong>{label}</strong><span>{total}</span></div></div><div className="course-progress"><div><span style={{ width: percent }} /></div><strong>{percent}</strong></div><span className="course-present"><Check size={14} />{present}</span></div>
}
