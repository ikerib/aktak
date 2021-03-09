
export ZSH="/home/appuser/.oh-my-zsh"

ZSH_THEME="kphoen"
DISABLE_AUTO_UPDATE="true"

# Uncomment the following line if you want to change the command execution time
# stamp shown in the history command output.
# You can set one of the optional three formats:
# "mm/dd/yyyy"|"dd.mm.yyyy"|"yyyy-mm-dd"
# or set a custom format using the strftime function format specifications,
# see 'man strftime' for details.
HIST_STAMPS="dd/mm/yyyy"
plugins=(git)

source $ZSH/oh-my-zsh.sh

alias c="clear"
alias l="ls -lah"
alias cc="php bin/console cache:clear"
alias sf="php bin/console"
