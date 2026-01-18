pipeline {
  agent {
    label 'raspberry'
  }

  environment {
    IMAGE_NAME = "wcis-backend"
    CI_DB_CONTAINER = "ci-mariadb"
    PROD_CONTAINER = "wcis-backend-prod"
    CI_NETWORK = "ci-net"
  }

  stages {

    stage('Context') {
      steps {
        echo "Branch: ${env.BRANCH_NAME}"
        echo "PR: ${env.CHANGE_ID ?: 'no'}"
        echo "Target: ${env.CHANGE_TARGET ?: 'n/a'}"
      }
    }

    /* =========================
       PR → DEV : CI
    ========================== */
    stage('CI - Prepare Network') {
      when {
        changeRequest target: 'dev'
      }
      steps {
        sh '''
          docker network rm ${CI_NETWORK} || true
          docker network create ${CI_NETWORK}
        '''
      }
    }

    stage('CI - Build Image') {
      when {
        changeRequest target: 'dev'
      }
      steps {
        sh 'docker build -t webcis-backend:ci .'
      }
    }

    stage('CI - Start MariaDB') {
      when {
        changeRequest target: 'dev'
      }
      steps {
        withCredentials([
          string(credentialsId: 'db_password', variable: 'DB_PASS'),
          string(credentialsId: 'db_user', variable: 'DB_USER'),
          string(credentialsId: 'db_name', variable: 'DB_NAME')
        ]) {
          sh '''
            docker rm -f ${CI_DB_CONTAINER} || true

            docker run -d \
              --name ${CI_DB_CONTAINER} \
              --network ${CI_NETWORK} \
              -e MARIADB_DATABASE=${DB_NAME} \
              -e MARIADB_USER=${DB_USER} \
              -e MARIADB_PASSWORD=${DB_PASS} \
              -e MARIADB_ROOT_PASSWORD=root \
              mariadb:11
          '''
        }
      }
    }

    stage('CI - Wait For MariaDB') {
      when {
        changeRequest target: 'dev'
      }
      steps {
        sh '''
          echo "Waiting for MariaDB..."
          for i in {1..20}; do
            docker exec ci-mariadb mariadb-admin ping \
              -uroot -proot --silent && break
            sleep 2
          done
        '''
      }
    }

    stage('CI - Run Migrations & Checks') {
      when {
        changeRequest target: 'dev'
      }
      steps {
        withCredentials([
          string(credentialsId: 'db_password', variable: 'DB_PASS'),
          string(credentialsId: 'db_user', variable: 'DB_USER'),
          string(credentialsId: 'db_name', variable: 'DB_NAME')
        ]) {
          sh '''
            docker run --rm \
              --network ${CI_NETWORK} \
              -e DB_CONNECTION=mariadb \
              -e DB_HOST=${CI_DB_CONTAINER} \
              -e DB_DATABASE=${DB_NAME} \
              -e DB_USERNAME=${DB_USER} \
              -e DB_PASSWORD=${DB_PASS} \
              webcis-backend:ci \
              sh -c "
                php artisan config:clear &&
                php artisan cache:clear &&
                php artisan migrate --force
              "
          '''

          sh '''
            docker run --rm webcis-backend:ci php artisan --version
            docker run --rm webcis-backend:ci composer --version
          '''
        }
      }
    }

    /* =========================
       MAIN : BUILD + DEPLOY
    ========================== */
    stage('Build Production Image') {
      when {
        branch 'main'
      }
      steps {
        sh 'docker build -t ${IMAGE_NAME}:latest .'
      }
    }

    stage('Deploy to Raspberry') {
      when {
        branch 'main'
      }
      steps {
        sh '''
          docker rm -f ${PROD_CONTAINER} || true

          docker run -d \
            --name ${PROD_CONTAINER} \
            -p 80:80 \
            ${IMAGE_NAME}:latest
        '''
      }
    }
  }

  post {
    always {
      sh '''
        docker rm -f ${CI_DB_CONTAINER} || true
        docker network rm ${CI_NETWORK} || true
        docker image prune -f || true
      '''
    }

    success {
      echo "Pipeline OK"
    }

    failure {
      echo "Pipeline FALLÓ"
    }
  }
}
