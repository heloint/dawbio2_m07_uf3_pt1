let SessionLoad = 1
let s:so_save = &g:so | let s:siso_save = &g:siso | setg so=0 siso=0 | setl so=-1 siso=-1
let v:this_session=expand("<sfile>:p")
silent only
silent tabonly
cd ~/dawbio2_m07_uf3_pt1/dawbio2_m07_uf3_pt1_Daniel_Majer
if expand('%') == '' && !&modified && line('$') <= 1 && getline(1) == ''
  let s:wipebuf = bufnr('%')
endif
let s:shortmess_save = &shortmess
if &shortmess =~ 'A'
  set shortmess=aoOA
else
  set shortmess=aoO
endif
badd +26 index.php
badd +42 controllers/MainController.php
badd +36 views/user/usermanage.php
badd +43 views/category/categorymanage.php
badd +63 model/StoreModel.php
argglobal
%argdel
$argadd index.php
set stal=2
tabnew +setlocal\ bufhidden=wipe
tabrewind
edit views/category/categorymanage.php
argglobal
balt views/user/usermanage.php
setlocal fdm=manual
setlocal fde=0
setlocal fmr={{{,}}}
setlocal fdi=#
setlocal fdl=0
setlocal fml=1
setlocal fdn=20
setlocal fen
silent! normal! zE
let &fdl = &fdl
let s:l = 43 - ((39 * winheight(0) + 28) / 56)
if s:l < 1 | let s:l = 1 | endif
keepjumps exe s:l
normal! zt
keepjumps 43
normal! 019|
tabnext
edit NetrwTreeListing
let s:save_splitbelow = &splitbelow
let s:save_splitright = &splitright
set splitbelow splitright
wincmd _ | wincmd |
vsplit
1wincmd h
wincmd w
let &splitbelow = s:save_splitbelow
let &splitright = s:save_splitright
wincmd t
let s:save_winminheight = &winminheight
let s:save_winminwidth = &winminwidth
set winminheight=0
set winheight=1
set winminwidth=0
set winwidth=1
exe 'vert 1resize ' . ((&columns * 40 + 136) / 272)
exe 'vert 2resize ' . ((&columns * 231 + 136) / 272)
argglobal
balt model/StoreModel.php
setlocal fdm=manual
setlocal fde=0
setlocal fmr={{{,}}}
setlocal fdi=#
setlocal fdl=0
setlocal fml=1
setlocal fdn=20
setlocal fen
silent! normal! zE
let &fdl = &fdl
let s:l = 6 - ((5 * winheight(0) + 28) / 56)
if s:l < 1 | let s:l = 1 | endif
keepjumps exe s:l
normal! zt
keepjumps 6
normal! 08|
lcd ~/dawbio2_m07_uf3_pt1/dawbio2_m07_uf3_pt1_Daniel_Majer
wincmd w
argglobal
if bufexists(fnamemodify("~/dawbio2_m07_uf3_pt1/dawbio2_m07_uf3_pt1_Daniel_Majer/model/StoreModel.php", ":p")) | buffer ~/dawbio2_m07_uf3_pt1/dawbio2_m07_uf3_pt1_Daniel_Majer/model/StoreModel.php | else | edit ~/dawbio2_m07_uf3_pt1/dawbio2_m07_uf3_pt1_Daniel_Majer/model/StoreModel.php | endif
if &buftype ==# 'terminal'
  silent file ~/dawbio2_m07_uf3_pt1/dawbio2_m07_uf3_pt1_Daniel_Majer/model/StoreModel.php
endif
balt ~/dawbio2_m07_uf3_pt1/dawbio2_m07_uf3_pt1_Daniel_Majer/controllers/MainController.php
setlocal fdm=manual
setlocal fde=0
setlocal fmr={{{,}}}
setlocal fdi=#
setlocal fdl=0
setlocal fml=1
setlocal fdn=20
setlocal fen
silent! normal! zE
let &fdl = &fdl
let s:l = 63 - ((44 * winheight(0) + 28) / 56)
if s:l < 1 | let s:l = 1 | endif
keepjumps exe s:l
normal! zt
keepjumps 63
normal! 0
wincmd w
2wincmd w
exe 'vert 1resize ' . ((&columns * 40 + 136) / 272)
exe 'vert 2resize ' . ((&columns * 231 + 136) / 272)
tabnext 2
set stal=1
if exists('s:wipebuf') && len(win_findbuf(s:wipebuf)) == 0 && getbufvar(s:wipebuf, '&buftype') isnot# 'terminal'
  silent exe 'bwipe ' . s:wipebuf
endif
unlet! s:wipebuf
set winheight=1 winwidth=20
let &shortmess = s:shortmess_save
let &winminheight = s:save_winminheight
let &winminwidth = s:save_winminwidth
let s:sx = expand("<sfile>:p:r")."x.vim"
if filereadable(s:sx)
  exe "source " . fnameescape(s:sx)
endif
let &g:so = s:so_save | let &g:siso = s:siso_save
set hlsearch
doautoall SessionLoadPost
unlet SessionLoad
" vim: set ft=vim :
